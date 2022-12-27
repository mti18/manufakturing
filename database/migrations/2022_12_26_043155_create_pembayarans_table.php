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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->enum('pembayaran', ['SalesOrder', 'Pembelian'])->default('Pembelian');
            
            $table->bigInteger('salesorder_id')->unsigned();
            $table->foreign('salesorder_id')->references('id')->on('sales_orders')
                  ->onDelete('restrict');
            $table->bigInteger('pembelian_id')->unsigned();
            $table->foreign('pembelian_id')->references('id')->on('pembelians')
                  ->onDelete('restrict');
            $table->integer('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')
                  ->onDelete('restrict');
            $table->integer('accbiaya_id')->unsigned()->nullable();
            $table->foreign('accbiaya_id')->references('id')->on('accounts')
                  ->onDelete('restrict');
        
            $table->date('tanggal');
            $table->enum('jenis_pembayaran', ['Tunai', 'Cek', 'Transfer'])->default('Tunai');
            $table->decimal('bayar', 12, 2)->default(0);
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
        Schema::dropIfExists('pembayarans');
    }
};
