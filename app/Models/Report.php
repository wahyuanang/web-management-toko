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
        'harga_per_pcs',
        'total_harga',
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
        // Auto calculate total_harga before creating/updating
        static::creating(function (Report $report) {
            if (!$report->harga_per_pcs && $report->assignment && $report->assignment->product) {
                $report->harga_per_pcs = $report->assignment->product->harga_per_pcs;
            }
            $report->total_harga = $report->jumlah_barang_dikirim * $report->harga_per_pcs;
        });

        static::updating(function (Report $report) {
            if (!$report->harga_per_pcs && $report->assignment && $report->assignment->product) {
                $report->harga_per_pcs = $report->assignment->product->harga_per_pcs;
            }
            $report->total_harga = $report->jumlah_barang_dikirim * $report->harga_per_pcs;
        });

        static::created(function (Report $report) {
            $assignment = $report->assignment;

            // Update status ke in_progress jika masih pending
            if ($assignment->status === 'pending') {
                $assignment->update(['status' => 'in_progress']);
            }

            // Update status ke done jika sudah mencapai atau melebihi target
            $totalDikirim = $assignment->reports()->sum('jumlah_barang_dikirim');
            if ($totalDikirim >= $assignment->qty_target) {
                $assignment->update(['status' => 'done']);
                
                // Auto-mark notifikasi sebagai read ketika assignment selesai
                $karyawan = $assignment->assignedUser;
                if ($karyawan) {
                    $karyawan->unreadNotifications()
                        ->where('data->assignment_id', $assignment->id)
                        ->get()
                        ->each(function ($notification) {
                            $notification->markAsRead();
                        });
                }
            }
        });

        static::updated(function (Report $report) {
            $assignment = $report->assignment;

            // Rekalkulasi total dikirim setelah report di-update
            $totalDikirim = $assignment->reports()->sum('jumlah_barang_dikirim');
            if ($totalDikirim >= $assignment->qty_target) {
                $assignment->update(['status' => 'done']);
                
                // Auto-mark notifikasi sebagai read ketika assignment selesai
                $karyawan = $assignment->assignedUser;
                if ($karyawan) {
                    $karyawan->unreadNotifications()
                        ->where('data->assignment_id', $assignment->id)
                        ->get()
                        ->each(function ($notification) {
                            $notification->markAsRead();
                        });
                }
            } elseif ($assignment->status === 'done' && $totalDikirim < $assignment->qty_target) {
                $assignment->update(['status' => 'in_progress']);
            }
        });

        static::deleted(function (Report $report) {
            $assignment = $report->assignment;

            // Rekalkulasi total dikirim setelah report dihapus
            $totalDikirim = $assignment->reports()->sum('jumlah_barang_dikirim');
            if ($totalDikirim < $assignment->qty_target && $assignment->status === 'done') {
                $assignment->update(['status' => 'in_progress']);
            } elseif ($totalDikirim == 0 && $assignment->status === 'in_progress') {
                $assignment->update(['status' => 'pending']);
            }
        });
    }
}
