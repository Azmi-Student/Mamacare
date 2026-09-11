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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Pasien
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete(); // Dokter
            $table->date('date');
            $table->string('time'); // Jam
            
            // Data dari Pasien
            $table->string('notes')->nullable(); // Keluhan awal / Jenis Pemeriksaan
            
            // Data Hasil Dokter (Baru)
            $table->text('diagnosis')->nullable(); // Diagnosa Dokter
            $table->text('prescription')->nullable(); // Resep Obat / Saran
            $table->string('image')->nullable(); // Foto Hasil Pemeriksaan (Opsional)
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};