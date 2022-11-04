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
        Schema::create('barang_kategori', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_mentah_id');
            $table->foreign('barang_mentah_id')->references('id')->on('barang_mentahs')->onDelete('cascade');
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('restrict');
            // $table->foreign('barang_jadi_id')->references('id')->on('barang_jadis')->onDelete('cascade');
            // $table->unsignedInteger('barnag_jadi_id');
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
        Schema::dropIfExists('barang_kategori');
    }
};
