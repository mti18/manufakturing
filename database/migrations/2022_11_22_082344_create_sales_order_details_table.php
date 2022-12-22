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
        Schema::create('sales_order_detail', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('volume');
            $table->decimal('harga', 13, 2)->default(0);
            $table->decimal('diskon', 13, 2)->default(0);
            $table->decimal('jumlah', 13, 2)->default(0);
            $table->integer('keterangan')->nullable();

            $table->bigInteger('salesorder_id')->unsigned();
            $table->foreign('salesorder_id')->references('id')->on('sales_orders')
                  ->onDelete('restrict');

            

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
        Schema::dropIfExists('sales_order_detail');
    }
};
