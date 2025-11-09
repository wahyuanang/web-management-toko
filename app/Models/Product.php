<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'kategori',
        'stok',
        'stok_minimum',
        'satuan',
        'harga_per_pcs',
        'deskripsi',
        'image',
    ];

    // Relasi ke Sales
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    // Relasi ke Assignments
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
