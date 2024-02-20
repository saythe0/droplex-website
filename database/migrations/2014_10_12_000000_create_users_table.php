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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('money')->default(0);
            $table->bigInteger('coins')->default(0);
            $table->string('password');
            $table->boolean('hd_skin')->default(0);
            $table->boolean('hd_cloak')->default(0);
            $table->string('group_id')->default('1');
            $table->string('referral')->nullable();
            $table->string('two_factor_secret')->nullable();
            $table->boolean('two_factor')->default(0);
            $table->json('backup_secret_codes')->nullable();
            $table->string('registration_ip')->nullable();
            $table->string('last_auth_ip')->nullable();
            $table->timestamp('last_auth_date')->nullable();
            $table->uuid('uuid')->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
