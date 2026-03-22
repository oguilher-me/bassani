<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyScheduleEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'assembly_schedule_id',
        'token',
        'nps_score',
        'comments',
        'photo_paths',
        'submitted_at',
    ];

    public function schedule()
    {
        return $this->belongsTo(AssemblySchedule::class, 'assembly_schedule_id');
    }
}

