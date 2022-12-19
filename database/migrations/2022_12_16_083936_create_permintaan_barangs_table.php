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
