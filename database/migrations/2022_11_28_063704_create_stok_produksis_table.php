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
        Schema::create('stok_produksis', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('stok_jadi')->default(0);

            $table->BigInteger('barangjadi_id')->unsigned()->nullable();
            $table->foreign('barangjadi_id')->references('id')->on('barang_jadis')->onDelete('restrict');

            $table->unsignedBigInteger('barang_produksi_id');
            $table->foreign('barang_produksi_id')->references('id')->on('barang_produksis')->onDelete('cascade');
            $table->unsignedBigInteger('barang_mentah_id');
            $table->foreign('barang_mentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

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
        Schema::dropIfExists('stok_produksis');
    }
};
