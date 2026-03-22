<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasPermission($permissionName): bool
    {
        return $this->role && $this->role->hasPermissionTo($permissionName);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function assembler(): HasOne
    {
        return $this->hasOne(Assembler::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->seller) {
                $user->seller->delete();
            }
        });
    }
}
