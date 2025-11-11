<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Assignment;
use App\Models\Report;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELASI
    public function assignmentsCreated()
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    public function assignmentsReceived()
    {
        return $this->hasMany(Assignment::class, 'assigned_to');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is karyawan
     */
    public function isKaryawan(): bool
    {
        return $this->hasRole('karyawan');
    }
}
