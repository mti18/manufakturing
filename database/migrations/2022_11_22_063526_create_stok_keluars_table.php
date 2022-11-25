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
        Schema::create('stok_keluars', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->enum('tipe_barang', ['barang_jadi', 'barang_mentah']);

            $table->bigInteger('barangjadi_id')->unsigned()->nullable();
            $table->foreign('barangjadi_id')->references('id')->on('barang_jadis')->onDelete('restrict');
            $table->bigInteger('barangmentah_id')->unsigned()->nullable();
            $table->foreign('barangmentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

            $table->enum('kualitas', ['bagus', 'jelek'])->default('bagus');
            $table->date('tanggal_keluar')->nullable();
            $table->bigInteger('barang_keluar');
            $table->string('keterangan')->nullable(); 
            
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
        Schema::dropIfExists('stok_keluars');
    }
};
