<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_chats_table.php

public function up()
{
    Schema::create('chats', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');   // ID Pasien
        $table->unsignedBigInteger('dokter_id'); // ID Dokter
        $table->unsignedBigInteger('sender_id'); // ID Pengirim (Bisa Pasien atau Dokter)
        $table->text('message');
        $table->boolean('is_read')->default(false);
        $table->timestamps();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('dokter_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('chats');
}
};
