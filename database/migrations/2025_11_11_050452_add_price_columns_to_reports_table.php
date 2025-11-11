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
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('harga_per_pcs', 15, 2)->default(0)->after('jumlah_barang_dikirim');
            $table->decimal('total_harga', 15, 2)->default(0)->after('harga_per_pcs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['harga_per_pcs', 'total_harga']);
        });
    }
};
