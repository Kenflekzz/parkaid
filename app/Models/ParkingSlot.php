<?php
// ── app/Models/ParkingSlot.php ────────────────────────────────

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $fillable = [
        'slot_id',
        'number',
        'floor',
        'type',
        'occupied',
        'distance',
        'last_updated',
    ];

    protected $casts = [
        'occupied' => 'boolean',
        'distance' => 'float',
    ];

    // ── Helper: return as array matching the JS frontend format ──
    public function toFrontendArray(): array
    {
        return [
            'id'       => $this->slot_id,
            'number'   => $this->number,
            'floor'    => $this->floor,
            'type'     => $this->type,
            'occupied' => $this->occupied,
            'time'     => $this->last_updated ?? 'just now',
            'distance' => $this->distance,
        ];
    }
}