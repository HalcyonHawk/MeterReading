<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user has a role that contains the needed permission.
     * Used by UserPolicy
     *
     * @param string|collection $role Role being checked for
     *
     * @return bool Return true if user has the requried role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->role->name === $role;
        }
        return $role->contains('name', $this->role->name);
    }

    /**
     * A user has many meter readings
     */
    public function meterReadings()
    {
        return $this->hasMany('App\Models\MeterReading', 'user_id', 'id');
    }

    /**
     * A user has a role
     */
    public function role()
    {
        return $this->hasOne('App\Models\Role', 'role_id', 'role_id');
    }
}
