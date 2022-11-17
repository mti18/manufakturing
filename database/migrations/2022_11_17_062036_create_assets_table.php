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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nm_assets');
            $table->year('tahun');
            $table->bigInteger('kelompok_id')->unsigned();
            $table->foreign('kelompok_id')->references('id')->on('kelompoks')->onDelete('cascade');
            $table->bigInteger('jenisasset_id')->unsigned();
            $table->foreign('jenisasset_id')->references('id')->on('jenisassets')->onDelete('cascade');
            $table->bigInteger('profile_id')->unsigned();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->integer('jumlah')->default('1');
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
        Schema::dropIfExists('assets');
    }
};
