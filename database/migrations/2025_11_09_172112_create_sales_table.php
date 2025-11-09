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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); 

            // Relasi ke products
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // Data transaksi
            $table->integer('qty');
            $table->decimal('harga_per_pcs', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('lokasi_penjualan');
            $table->date('tanggal_penjualan');
            $table->text('note')->nullable();
            $table->enum('status_pengiriman', ['pending', 'proses', 'diantar', 'selesai'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};