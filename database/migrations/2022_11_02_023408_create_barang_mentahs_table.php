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
        Schema::create('barang_mentahs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nm_barangmentah');
            $table->bigInteger('stok')->default(0);

            $table->BigInteger('barangsatuan_id')->unsigned()->nullable();
            $table->foreign('barangsatuan_id')->references('id')->on('barang_satuans')->onDelete('cascade');
            
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
        Schema::dropIfExists('barang_mentahs');
    }
};
