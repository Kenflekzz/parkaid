<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\ManagementLog;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    // Initialize default 4 slots if cache is empty - SWAPPED B-01 and B-02
    if (!Cache::has('parking_slots')) {
        $defaultSlots = [
            ['id' => 'A-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'A-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'B-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'B-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
        ];
        Cache::put('parking_slots', $defaultSlots, now()->addHours(24));
    }
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/history', function () {
    return view('history');
})->middleware(['auth', 'verified'])->name('history');

Route::get('/parking-floors', function () {
    // Initialize default 4 slots if cache is empty - SWAPPED B-01 and B-02
    if (!Cache::has('parking_slots')) {
        $defaultSlots = [
            ['id' => 'A-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'A-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'B-02', 'number' => '02', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
            ['id' => 'B-01', 'number' => '01', 'floor' => 'Ground Floor', 'type' => 'Regular', 'occupied' => false, 'time' => 'just now'],
        ];
        Cache::put('parking_slots', $defaultSlots, now()->addHours(24));
    }
    return view('parking-floors');
})->middleware(['auth', 'verified'])->name('parking.floors');

// ── TEST AUTH ROUTE (for debugging) ──
Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user_name' => auth()->user()?->name,
        'session_id' => session()->getId(),
    ]);
})->middleware('auth');

// ── DEBUG MANAGEMENT LOG ROUTE (for testing) ──
Route::any('/debug-management-log', function (Request $request) {
    return response()->json([
        'method' => $request->method(),
        'session_id' => session()->getId(),
        'auth_check' => auth()->check(),
        'user_id' => auth()->id(),
        'user' => auth()->user(),
        'input' => $request->all(),
    ]);
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── MANAGEMENT LOG ROUTE (with proper authentication) ──
    Route::post('/management-log', function (Request $request) {
        // Debug logging
        \Log::info('===== MANAGEMENT LOG REQUEST =====');
        \Log::info('Request path: ' . $request->path());
        \Log::info('Session ID: ' . session()->getId());
        \Log::info('Auth check (web route): ' . (auth()->check() ? 'true' : 'false'));
        \Log::info('User ID from auth: ' . auth()->id());
        \Log::info('User name: ' . (auth()->user()?->name ?? 'null'));
        \Log::info('Request data: ', $request->all());
        
        // Ensure user is authenticated
        if (!auth()->check()) {
            \Log::error('Management log attempted without authentication');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        $validated = $request->validate([
            'action'   => 'required|in:slot_added,slot_deleted,space_added,space_deleted',
            'slot_id'  => 'nullable|string',
            'floor'    => 'nullable|string',
            'type'     => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        
        \Log::info('Creating management log', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $validated['action']
        ]);

        $log = ManagementLog::create([
            'user_id'    => $user->id,  // Explicitly use the authenticated user's ID
            'action'     => $validated['action'],
            'slot_id'    => $validated['slot_id'] ?? null,
            'floor'      => $validated['floor'] ?? null,
            'type'       => $validated['type'] ?? null,
            'quantity'   => $validated['quantity'] ?? 1,
            'logged_at'  => now(),
        ]);

        \Log::info('Management log created - ID: ' . $log->id . ', user_id: ' . ($log->user_id ?? 'null'));

        return response()->json([
            'success' => true,
            'log' => $log,
            'user' => [
                'id' => $user->id,
                'name' => $user->name
            ],
            'debug_user_id' => $user->id,
            'debug_auth_check' => auth()->check(),
        ]);
    })->name('management.log');
});

require __DIR__.'/auth.php';