<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name');
            $table->string('nip')->unique(); // login by WA or Login By NIP or Email
            $table->string('whatsapp')->unique(); // login by WA or Login By NIP or Email
            $table->string('email')->unique(); // login by WA or Login By NIP or Email
            $table->string('password');

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birth_date')->nullable();

            $table->enum('level', ['admin', 'user'])->default('user');
            $table->enum('status', ['tetap', 'kontrak', 'magang'])->default('kontrak');
            $table->boolean('is_active')->default(false);

            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('restrict');
            $table->foreignId('user_group_id')->nullable()->constrained('user_groups')->onDelete('restrict');

            $table->string('otp')->nullable();
            $table->string('otp_expire')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
