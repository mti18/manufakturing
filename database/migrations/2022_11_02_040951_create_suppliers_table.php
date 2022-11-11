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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama');
            $table->string('telepon');
            $table->string('kode')->unique();
            $table->string('npwp')->nullable();
            $table->string('nppkp')->nullable();
            $table->string('nama_kontak')->nullable();
            $table->string('telp_kontak')->nullable();
            $table->string('alamat');
            $table->enum('tipe', ['supplier', 'customer']);

            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->integer('provinsi_id')->unsigned();
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('restrict');
            $table->integer('kab_kota_id')->unsigned();
            $table->foreign('kab_kota_id')->references('id')->on('kotas')->onDelete('restrict');
            $table->integer('kecamatan_id')->unsigned();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('restrict');

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
        Schema::dropIfExists('suppliers');
    }
};
