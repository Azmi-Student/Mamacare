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
    Schema::create('kehamilan_mamas', function (Blueprint $table) {
        $table->id();
        // foreignId harus sinkron dengan tabel users
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->date('hpht');
        $table->json('ai_data')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehamilan_mamas');
    }
};
