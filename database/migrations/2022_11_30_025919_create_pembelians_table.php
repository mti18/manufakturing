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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->bigInteger('salesorder_id')->unsigned()->nullable();
            $table->foreign('salesorder_id')->references('id')->on('sales_orders')
                  ->onDelete('cascade');
            $table->bigInteger('profile_id')->unsigned();
            $table->foreign('profile_id')->references('id')->on('profiles')
                  ->onDelete('restrict');                 
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers')
                  ->onDelete('restrict');

            $table->string('no_surat');
            $table->date('tgl_permintaan');
            $table->string('bukti_permintaan');

            $table->string('no_surat_pembelian');
            $table->bigInteger('diketahui_oleh')->unsigned();
            $table->foreign('diketahui_oleh')->references('id')->on('users')
                  ->onDelete('restrict');
            $table->date('tgl_po');
            $table->enum('jenis_pembayaran', ['Tunai', 'Kredit'])->default('Tunai');
            $table->string('no_po_pembelian');
            $table->integer('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')
                  ->onDelete('restrict');
            $table->string('no_surat_jalan');
            $table->string('tempo')->nullable();

            $table->string('keterangan')->nullable();
            $table->decimal('jml_penjualan', 13, 2)->default(0);
            $table->decimal('diskon', 13, 2)->default(0);
            $table->decimal('uangmuka', 13, 2)->default(0);
            $table->decimal('pajak', 13, 2)->default(0); 
            $table->decimal('ppn', 13, 2)->default(0);
            $table->decimal('netto', 13, 2)->default(0);

            $table->string('nomor')->nullable();
            $table->enum('pembayaran', ['no', 'yes'])->default('no');
            $table->enum('tipe', ['1', '2'])->default('1'); // (1) Pembelian // (2) Pembelian Internal
            $table->enum('acc_pimpinan', ['N', 'Y'])->default('N');
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
        Schema::dropIfExists('pembelians');
    }
};
