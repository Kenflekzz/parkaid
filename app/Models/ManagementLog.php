<?php
// ── app/Models/ManagementLog.php ──────────────────────────────

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementLog extends Model
{
    protected $fillable = [
        'user_id',     // ADD THIS - it was missing!
        'action',
        'slot_id',
        'floor',
        'type',
        'quantity',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'quantity'  => 'integer',
    ];

    // ── Human-readable label for each action ──
    public function actionLabel(): string
    {
        return match($this->action) {
            'slot_added'    => 'Slot Added',
            'slot_deleted'  => 'Slot Deleted',
            'space_added'   => 'Parking Space Added',
            'space_deleted' => 'Parking Space Deleted',
            default         => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}