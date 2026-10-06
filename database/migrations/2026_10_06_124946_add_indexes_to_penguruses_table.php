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
        Schema::table('penguruses', function (Blueprint $table) {
            $table->index(['periode', 'aktif', 'urutan'], 'idx_penguruses_periode_aktif_urutan');
            $table->index('jabatan', 'idx_penguruses_jabatan');
            $table->index('nama', 'idx_penguruses_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penguruses', function (Blueprint $table) {
            $table->dropIndex('idx_penguruses_periode_aktif_urutan');
            $table->dropIndex('idx_penguruses_jabatan');
            $table->dropIndex('idx_penguruses_nama');
        });
    }
};
