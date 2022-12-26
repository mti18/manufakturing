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
        Schema::create('retur_barangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->bigInteger('jumlah')->unsigned();
            $table->date('tanggal');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');

            $table->bigInteger('salesorder_detail_id')->unsigned();
            $table->foreign('salesorder_detail_id')->references('id')->on('sales_order_detail')
                  ->onDelete('cascade');
                  
            $table->enum('status', [0, 1, 2])->default(0); //(1) Reject (2) Accept
          $table->string('keterangan')->nullable();

            $table->enum('acc_pimpinan', ['N', 'Y'])->default('N');
            
                  // $table->bigInteger('salesorder_detail_id')->unsigned();
                  // $table->foreign('salesorder_detail_id')->references('id')->on('sales_order_detail')
                  //       ->onDelete('cascade');

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
        Schema::dropIfExists('retur_barangs');
    }
};
