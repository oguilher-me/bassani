<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assembler extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'cpf',
        'phone',
        'email',
        'type',
        'address',
        'city',
        'state',
        'active',
        'photo',
    ];

    protected $casts = [
        'active' => 'boolean',
        'type' => \App\Enums\AssemblerTypeEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assemblySchedules()
    {
        return $this->belongsToMany(AssemblySchedule::class, 'assembly_schedule_assembler')->withPivot('commission_value', 'confirmation_status', 'assembler_notes');
    }
}
