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
        Schema::create('permintaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->enum('tipe_barang', ['barang_jadi', 'barang_mentah']);

            $table->bigInteger('barangjadi_id')->unsigned()->nullable();
            $table->foreign('barangjadi_id')->references('id')->on('barang_jadis')->onDelete('restrict');
            $table->bigInteger('barangmentah_id')->unsigned()->nullable();
            $table->foreign('barangmentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');
            
            $table->date('tanggal');
            $table->date('tanggal_permintaan')->nullable();
            $table->string('no_bukti_permintaan')->nullable();

            $table->enum('status', ['1', '2', '3'])->default('1'); // (1) pending, (2) procces, (3)done
            $table->enum('tipe', ['pembelian', 'internal'])->default('pembelian');

            $table->bigInteger('volume');
            $table->decimal('harga', 12, 2);
            $table->string('jumlah', 12, 2);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('permintaan_barangs');
    }
};
