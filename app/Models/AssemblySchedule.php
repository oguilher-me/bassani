<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssemblySchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'scheduled_date',
        'estimated_duration',
        'start_time',
        'end_time',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function assemblers()
    {
        return $this->belongsToMany(Assembler::class, 'assembly_schedule_assembler')->withPivot('commission_value', 'confirmation_status', 'assembler_notes', 'started_at', 'start_photo_path', 'start_latitude', 'start_longitude', 'start_accuracy', 'finished_at', 'finish_notes', 'finish_photo_paths', 'finish_pending_reason');
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\AssemblyExpense::class);
    }
}
