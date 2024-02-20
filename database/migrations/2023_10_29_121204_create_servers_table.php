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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //имя сервера
            $table->string('alt_name'); //имя сервера
            $table->string('description'); //описание сервера
            $table->string('version'); // версия сервера
            $table->string('icon')->nullable(); // имя иконки
            $table->string('image')->nullable(); // имя фотографии
            $table->boolean('active')->default(1); // активен или нет
            $table->boolean('donate')->default(1); // покупка доната
            $table->string('group_vk_id')->nullable(); // группа вк
            $table->string('ip');
            $table->string('default_port');
            $table->string('rcon_port');
            $table->string('rcon_password');
            $table->json('database')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
