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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->string('judul_program')->unique();
            $table->string('slug')->unique();
            $table->string('location_name')->default('NUL');
            $table->date('open_regis_panitia')->nullable();
            $table->date('close_regis_panitia')->nullable();
            $table->text('gform_panitia')->nullable();
            $table->date('open_regis_peserta');
            $table->date('close_regis_peserta');
            $table->text('gform_peserta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
