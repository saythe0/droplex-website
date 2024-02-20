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
        Schema::create('launcher_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->uuid('user_uuid');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('server_id')->nullable();
            $table->string('expire_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launcher_sessions');
    }
};
