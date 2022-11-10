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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama');
            $table->string('telepon');
            $table->string('npwp');
            $table->string('fax');
            $table->string('pimpinan');
            $table->string('alamat');

            $table->integer('provinsi_id')->unsigned();
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('restrict');
            $table->integer('kab_kota_id')->unsigned();
            $table->foreign('kab_kota_id')->references('id')->on('kotas')->onDelete('restrict');
            $table->integer('kecamatan_id')->unsigned();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('restrict');
            $table->integer('kelurahan_id')->unsigned();
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('restrict');

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
        Schema::dropIfExists('profiles');
    }
};
