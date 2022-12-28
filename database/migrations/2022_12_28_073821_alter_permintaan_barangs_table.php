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
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->bigInteger('pembelian_id')->nullable()->unsigned();
            $table->foreign('pembelian_id')->references('id')->on('pembelians')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropForeign(['pembelian_id']);
            $table->dropColumn('pembelian_id');
        });
    }
};
