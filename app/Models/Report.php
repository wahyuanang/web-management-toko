<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
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
     * Relasi ke Assignment
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}