<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\NewAssignmentNotification;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'lokasi_tujuan',
        'assigned_to',
        'product_id',
        'qty_target',
        'priority',
        'status',
        'deadline',
        'created_by',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Boot method untuk auto trigger events
     */
    protected static function booted(): void
    {
        // Trigger notifikasi saat assignment baru dibuat
        static::created(function (Assignment $assignment) {
            $karyawan = $assignment->assignedUser;
            if ($karyawan) {
                $karyawan->notify(new NewAssignmentNotification($assignment));
            }
        });
    }

    /**
     * Assignment assigned to User (karyawan)
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Assignment belongs to Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Assignment created by User (admin)
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Assignment has many Reports
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get total barang yang sudah dikirim
     */
    public function getTotalDikirimAttribute(): int
    {
        return $this->reports()->sum('jumlah_barang_dikirim');
    }

    /**
     * Get progress percentage
     */
    public function getProgressAttribute(): float
    {
        if ($this->qty_target == 0) return 0;
        return min(100, ($this->total_dikirim / $this->qty_target) * 100);
    }

    /**
     * Check if assignment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'done' || $this->total_dikirim >= $this->qty_target;
    }

    /**
     * Check if assignment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->deadline && now()->isAfter($this->deadline) && !$this->isCompleted();
    }
}
