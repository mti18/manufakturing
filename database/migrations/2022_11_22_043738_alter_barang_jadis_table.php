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
            $table->bigInteger('stok_bagus')->default(0);
            $table->bigInteger('stok_jelek')->default(0);

            $table->dropColumn('stok');
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
            $table->dropColumn('stok_bagus');
            $table->dropColumn('stok_jelek');

            $table->bigInteger('stok');
        });
    }
};
