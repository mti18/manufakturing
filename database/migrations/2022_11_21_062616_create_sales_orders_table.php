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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();       
            $table->uuid('uuid')->unique();

            $table->bigInteger('profile_id')->unsigned();
            $table->foreign('profile_id')->references('id')->on('profiles')
                  ->onDelete('restrict');
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers')
                  ->onDelete('restrict');


            $table->bigInteger('diketahui_oleh')->unsigned();
            $table->foreign('diketahui_oleh')->references('id')->on('users')
                  ->onDelete('restrict');
            $table->integer('jumlah_paket');
            $table->string('bukti_pesan')->nullable(); // no_bukti
            $table->enum('jenis_pembayaran', ['Tunai', 'Cek', 'Transfer', 'Free'])->default('Tunai');
            $table->integer('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')
                  ->onDelete('restrict');
            $table->enum('pembayaran', ['no', 'yes'])->default('no'); 
            $table->date('tgl_pesan');
            $table->date('tgl_pengiriman');
            $table->string('tempo')->nullable();

            $table->enum('status', ['draft', 'process', 'ready', 'done'])->default('draft'); 

            $table->string('keterangan')->nullable();
            $table->decimal('total', 13, 2)->default(0);
            $table->decimal('diskon', 13, 2)->default(0);
            $table->decimal('uangmuka', 13, 2)->default(0);
            $table->decimal('pph', 13, 2)->default(0);
            $table->decimal('ppn', 13, 2)->default(0);
            $table->decimal('netto', 13, 2)->default(0);


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
        Schema::dropIfExists('sales_orders');
    }
};
