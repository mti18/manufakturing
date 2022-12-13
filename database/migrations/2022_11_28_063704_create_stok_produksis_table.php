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

            $table->date('tanggal_produksi')->nullable();
            $table->BigInteger('barang_produksi_id')->unsigned()->nullable();
            $table->foreign('barang_produksi_id')->references('id')->on('barang_produksis')->onDelete('cascade');
    
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
