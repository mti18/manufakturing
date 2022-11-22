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
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->enum('tipe_barang', ['`Barang Jadi`', 'Barang Jelek']);

            $table->bigInteger('barangjadi_id')->unsigned()->nullable();
            $table->foreign('barangjadi_id')->references('id')->on('barang_jadis')->onDelete('restrict');
            $table->bigInteger('barangmentah_id')->unsigned()->nullable();
            $table->foreign('barangmentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

            $table->enum('kualitas', ['Bagus', 'Jelek'])->default('Bagus');
            $table->date('tanggal_masuk');
            $table->bigInteger('barang_masuk');
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
        Schema::dropIfExists('stok_masuks');
    }
};
