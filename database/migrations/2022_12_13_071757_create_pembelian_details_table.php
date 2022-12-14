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
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->bigInteger('salesorder_id')->nullable()->unsigned();
            $table->foreign('salesorder_id')->references('id')->on('sales_orders')
                  ->onDelete('cascade');

            $table->bigInteger('pembelian_id')->nullable()->unsigned();
            $table->foreign('pembelian_id')->references('id')->on('pembelians')
                  ->onDelete('cascade');

            $table->integer('jumlah')->default(0);
            $table->decimal('harga', 13, 2)->default(0);

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
        Schema::dropIfExists('pembelian_details');
    }
};
