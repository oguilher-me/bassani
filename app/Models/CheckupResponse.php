<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckupResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkup_id',
        'checklist_item_id',
        'is_ok',
        'observation',
    ];

    protected $casts = [
        'is_ok' => 'boolean',
    ];

    /**
     * Get the vehicle checkup this response belongs to.
     */
    public function checkup(): BelongsTo
    {
        return $this->belongsTo(VehicleCheckup::class, 'checkup_id');
    }

    /**
     * Get the checklist item this response is for.
     */
    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }
}
