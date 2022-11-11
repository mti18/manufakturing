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
        Schema::table('barang_jadis', function (Blueprint $table) {
            $table->BigInteger('gudang_id')->unsigned()->nullable();
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onDelete('restrict');

            $table->double('harga', 12, 2);
            $table->string('kd_barang_jadi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_jadis', function (Blueprint $table) {
            $table->dropForeign(['gudang_id']);
            $table->dropColumn('gudang_id');

            $table->dropColumn('harga');
            $table->dropColumn('kd_barang_jadi');
        });
    }
};
