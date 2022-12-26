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
        Schema::table('sales_order_detail', function (Blueprint $table) {
            $table->enum('status', ['0', '1', '2', '3', '4', '5', '6'])->default('0'); // (1)Reject (2)Accept (3)Konfirmasi (4)Produksi (5)Pembelian (6)retur

            $table->uuid('uuid')->unique();

            $table->bigInteger('barangjadi_id')->unsigned()->nullable();
            $table->foreign('barangjadi_id')->references('id')->on('barang_jadis')->onDelete('restrict');
            $table->bigInteger('barangmentah_id')->unsigned()->nullable();
            $table->foreign('barangmentah_id')->references('id')->on('barang_mentahs')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_detail', function (Blueprint $table) {
            $table->dropColumn('status');

            $table->dropForeign(['barangjadi_id']);
            $table->dropColumn('barangjadi_id');
            $table->dropForeign(['barangmentah_id']);
            $table->dropColumn('barangmentah_id');
        });
    }
};
