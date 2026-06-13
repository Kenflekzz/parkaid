<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\ParkingSlot;
use App\Models\ParkingHistory;
use App\Models\ManagementLog;

// ── Default slots to seed if the table is empty ──────────────
function seedDefaultSlots(): void
{
    if (ParkingSlot::count() === 0) {
        $defaults = [
            ['slot_id' => 'A-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular'],
            ['slot_id' => 'A-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular'],
            ['slot_id' => 'B-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular'],
            ['slot_id' => 'B-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular'],
        ];

        foreach ($defaults as $slot) {
            ParkingSlot::create([
                ...$slot,
                'occupied'     => false,
                'distance'     => null,
                'last_updated' => 'just now',
            ]);
        }
    }
}

// ═══════════════════════════════════════════════════════════
// PUBLIC ROUTES (no auth required)
// ═══════════════════════════════════════════════════════════

// ── GET all slots with caching (fast polling) ─────────────
Route::get('/slots', function () {
    seedDefaultSlots();

    // Cache for 0.5 seconds - reduces database load while keeping updates fresh
    $slots = Cache::remember('all_slots', 0.5, function () {
        return ParkingSlot::orderBy('slot_id')->get()
            ->map(fn($s) => [
                'id' => $s->slot_id,
                'number' => $s->number,
                'floor' => $s->floor,
                'type' => $s->type,
                'occupied' => (bool)$s->occupied,
                'time' => $s->last_updated ?: 'just now',
                'distance' => $s->distance
            ]);
    });

    return response()->json($slots);
});

// ── ESP32 IoT Endpoint ────────────────────────────────────
Route::post('/update-slot-status', function (Request $request) {
    \Log::info('ESP32 Update Request Received', ['payload' => $request->all()]);

    $expectedApiKey = env('ARDUINO_API_KEY', '8f7d3a2e9b1c5f4a8e2d6c7b9a1e3f5d');

    if ($request->input('api_key') !== $expectedApiKey) {
        \Log::warning('Invalid API key from ESP32');
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    seedDefaultSlots();

    // ── Batch request (multiple slots from ESP32) ─────────
    if ($request->has('slots')) {
        $slots = $request->input('slots');
        \Log::info('Processing ' . count($slots) . ' slots from ESP32');
        
        $updatedCount = 0;
        $currentTimestamp = now('Asia/Manila')->format('h:i:s A');

        foreach ($slots as $update) {
            try {
                $slotId = $update['slot_id'];
                $newStatus = $update['status'];
                $newOccupied = ($newStatus === 'occupied');
                $distance = isset($update['distance']) ? (float)$update['distance'] : null;
                
                $slot = ParkingSlot::where('slot_id', $slotId)->first();

                if ($slot) {
                    $oldOccupied = $slot->occupied;
                    
                    $slot->occupied = $newOccupied;
                    $slot->distance = $distance;
                    $slot->last_updated = $currentTimestamp;
                    $slot->save();
                    
                    // ALWAYS log to history when status changes
                    if ($oldOccupied !== $newOccupied) {
                        ParkingHistory::create([
                            'slot_id' => $slotId,
                            'floor' => $slot->floor,
                            'type' => $slot->type,
                            'status' => $newStatus,
                            'distance' => $distance,
                            'changed_at' => now('Asia/Manila'),
                            'user_id' => null,
                        ]);
                        \Log::info("✅ History logged: Slot {$slotId} changed from " . ($oldOccupied ? 'occupied' : 'vacant') . " -> {$newStatus} at {$currentTimestamp}");
                    }
                    $updatedCount++;
                } else {
                    $number = substr($slotId, strpos($slotId, '-') + 1);
                    $slot = ParkingSlot::create([
                        'slot_id' => $slotId,
                        'number' => $number,
                        'floor' => 'Ground Floor',
                        'type' => 'Regular',
                        'occupied' => $newOccupied,
                        'distance' => $distance,
                        'last_updated' => $currentTimestamp,
                    ]);
                    
                    ParkingHistory::create([
                        'slot_id' => $slotId,
                        'floor' => 'Ground Floor',
                        'type' => 'Regular',
                        'status' => $newStatus,
                        'distance' => $distance,
                        'changed_at' => now('Asia/Manila'),
                        'user_id' => null,
                    ]);
                    \Log::info("✅ History logged: New slot {$slotId} created with status {$newStatus}");
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                \Log::error('Error processing slot update: ' . $e->getMessage());
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

        // Clear cache after updates
        Cache::forget('all_slots');

        return response()->json([
            'success' => true,
            'updated' => $updatedCount,
            'timestamp' => now('Asia/Manila')->toDateTimeString()
        ]);
    }

    return response()->json(['error' => 'No slots data provided'], 400);
    
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ── GET slot status (for ESP32 debug check) ───────────────
Route::get('/slots-status', function (Request $request) {
    $apiKey = $request->query('api_key');
    $expectedApiKey = env('ARDUINO_API_KEY', '8f7d3a2e9b1c5f4a8e2d6c7b9a1e3f5d');

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json(
        ParkingSlot::orderBy('slot_id')
            ->get()
            ->map(fn($s) => [
                'id' => $s->slot_id,
                'number' => $s->number,
                'floor' => $s->floor,
                'type' => $s->type,
                'occupied' => (bool)$s->occupied,
                'time' => $s->last_updated ?: 'just now',
                'distance' => $s->distance
            ])
    );
});

// ── GET init slots (reset to defaults) ────────────────────
Route::get('/init-slots', function () {
    ParkingSlot::truncate();
    ParkingHistory::truncate();
    Cache::forget('all_slots');

    $defaults = [
        ['slot_id' => 'A-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular'],
        ['slot_id' => 'A-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular'],
        ['slot_id' => 'B-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular'],
        ['slot_id' => 'B-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular'],
    ];

    foreach ($defaults as $slot) {
        ParkingSlot::create([
            ...$slot,
            'occupied' => false,
            'distance' => null,
            'last_updated' => 'just now',
        ]);
    }

    return response()->json([
        'success' => true,
        'slots' => ParkingSlot::all()->map(fn($s) => [
            'id' => $s->slot_id,
            'number' => $s->number,
            'floor' => $s->floor,
            'type' => $s->type,
            'occupied' => (bool)$s->occupied,
            'time' => $s->last_updated ?: 'just now',
            'distance' => $s->distance
        ])
    ]);
});

// ── Test endpoint to manually update a slot ───────────────
Route::get('/test-update-slot', function () {
    $slot = ParkingSlot::where('slot_id', 'A-01')->first();
    if ($slot) {
        $slot->occupied = !$slot->occupied;
        $slot->last_updated = now('Asia/Manila')->format('h:i:s A');
        $slot->save();
        Cache::forget('all_slots');
        
        ParkingHistory::create([
            'slot_id' => 'A-01',
            'floor' => $slot->floor,
            'type' => $slot->type,
            'status' => $slot->occupied ? 'occupied' : 'vacant',
            'distance' => null,
            'changed_at' => now('Asia/Manila'),
            'user_id' => null
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Toggled A-01 to " . ($slot->occupied ? 'occupied' : 'vacant'),
            'history_count' => ParkingHistory::count()
        ]);
    }
    return response()->json(['error' => 'Slot not found'], 404);
});

// ── Test endpoint to check history ────────────────────────
Route::get('/test-history', function () {
    return response()->json([
        'history_count' => ParkingHistory::count(),
        'latest_history' => ParkingHistory::latest()->take(5)->get(),
        'all_slots' => ParkingSlot::all()->map(fn($s) => [
            'id' => $s->slot_id,
            'occupied' => $s->occupied,
            'last_updated' => $s->last_updated
        ])
    ]);
});


// ═══════════════════════════════════════════════════════════
// AUTHENTICATED ROUTES (require logged-in user)
// ═══════════════════════════════════════════════════════════

Route::middleware(['web', 'auth'])->group(function () {

    Route::post('/sync-slots', function (Request $request) {
        $incomingSlots = $request->input('slots', []);

        foreach ($incomingSlots as $slotData) {
            ParkingSlot::updateOrCreate(
                ['slot_id' => $slotData['id']],
                [
                    'number' => $slotData['number'] ?? '00',
                    'floor' => $slotData['floor'] ?? 'Ground Floor',
                    'type' => $slotData['type'] ?? 'Regular',
                    'occupied' => $slotData['occupied'] ?? false,
                    'distance' => $slotData['distance'] ?? null,
                    'last_updated' => $slotData['time'] ?? now('Asia/Manila')->format('h:i:s A'),
                ]
            );
        }

        $incomingIds = collect($incomingSlots)->pluck('id')->filter()->values();
        if ($incomingIds->isNotEmpty()) {
            ParkingSlot::whereNotIn('slot_id', $incomingIds)->delete();
        }
        
        Cache::forget('all_slots');

        return response()->json(['success' => true]);
    });

    Route::post('/management-log', function (Request $request) {
        $validated = $request->validate([
            'action' => 'required|in:slot_added,slot_deleted,space_added,space_deleted',
            'slot_id' => 'nullable|string',
            'floor' => 'nullable|string',
            'type' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $log = ManagementLog::create([
            'user_id' => auth()->id(),
            'action' => $validated['action'],
            'slot_id' => $validated['slot_id'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'type' => $validated['type'] ?? null,
            'quantity' => $validated['quantity'] ?? 1,
            'logged_at' => now('Asia/Manila'),
        ]);

        return response()->json(['success' => true, 'log' => $log]);
    });

    Route::get('/history', function () {
        $history = ParkingHistory::with('user')
            ->orderByDesc('changed_at')
            ->limit(200)
            ->get()
            ->map(fn($h) => [
                'slot_id' => $h->slot_id,
                'floor' => $h->floor,
                'type' => $h->type,
                'status' => $h->status,
                'distance' => $h->distance,
                'timestamp' => $h->changed_at->format('Y-m-d h:i:s A'),
                'date' => $h->changed_at->format('M d, Y'),
                'time' => $h->changed_at->format('h:i:s A'),
                'user' => $h->user ? ['id' => $h->user->id, 'name' => $h->user->name] : null,
            ]);

        return response()->json($history);
    });

    Route::get('/management-logs', function () {
        $logs = ManagementLog::with('user')
            ->orderByDesc('logged_at')
            ->limit(200)
            ->get()
            ->map(fn($log) => [
                'id' => $log->id,
                'action' => $log->action,
                'action_label' => match($log->action) {
                    'slot_added' => 'Slot Added',
                    'slot_deleted' => 'Slot Deleted',
                    'space_added' => 'Space Added',
                    'space_deleted' => 'Space Deleted',
                    default => 'Action',
                },
                'slot_id' => $log->slot_id,
                'floor' => $log->floor,
                'type' => $log->type,
                'quantity' => $log->quantity,
                'date' => $log->logged_at?->format('M d, Y'),
                'time' => $log->logged_at?->format('h:i:s A'),
                'user' => $log->user ? ['id' => $log->user->id, 'name' => $log->user->name] : null,
            ]);

        return response()->json($logs);
    });

    Route::delete('/history', function () {
        ParkingHistory::truncate();
        return response()->json(['success' => true]);
    });

    Route::delete('/management-logs', function () {
        ManagementLog::truncate();
        return response()->json(['success' => true]);
    });

});