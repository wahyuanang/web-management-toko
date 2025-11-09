<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->onDelete('cascade'); // kalau assignment dihapus, laporan ikut hilang

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // kalau user dihapus, laporan ikut hilang

            $table->integer('jumlah_barang_dikirim');
            $table->text('lokasi');
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->string('foto_bukti_2')->nullable();
            $table->dateTime('waktu_laporan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};