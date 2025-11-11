<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'assignment_id',
        'jumlah_barang_dikirim',
        'lokasi',
        'catatan',
        'foto_bukti',
        'foto_bukti_2',
        'waktu_laporan',
    ];

    protected $casts = [
        'waktu_laporan' => 'datetime',
    ];

    /**
     * Report belongs to User (karyawan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Report belongs to Assignment
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Boot method to auto update assignment status
     */
    protected static function booted(): void
    {
        static::created(function (Report $report) {
            $assignment = $report->assignment;

            // Update status ke in_progress jika masih pending
            if ($assignment->status === 'pending') {
                $assignment->update(['status' => 'in_progress']);
            }

            // Update status ke done jika sudah mencapai target
            if ($assignment->total_dikirim >= $assignment->qty_target) {
                $assignment->update(['status' => 'done']);
            }
        });
    }
}
