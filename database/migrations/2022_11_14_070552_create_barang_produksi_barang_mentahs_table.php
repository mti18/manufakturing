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
        Schema::create('barang_produksi_barang_mentah', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('barang_produksi_id');
            $table->foreign('barang_produksi_id')->references('id')->on('barang_produksis')->onDelete('cascade');
            $table->unsignedBigInteger('barang_mentah_id');
            $table->foreign('barang_mentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

            $table->unsignedBigInteger('satuan_id');
            $table->foreign('satuan_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

            $table->bigInteger('stok_digunakan')->default(0);

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
        Schema::dropIfExists('barang_produksi_barang_mentah');
    }
};
