<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Dokter
        $table->string('title');
        $table->string('slug')->unique(); // Untuk URL (contoh: judul-artikel-1)
        $table->string('category'); // Harian, Mingguan, Trimester 1, dll
        $table->text('excerpt')->nullable(); // Ringkasan pendek
        $table->longText('content'); // Isi artikel (HTML)
        $table->string('image')->nullable(); // Path gambar
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->integer('views')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
