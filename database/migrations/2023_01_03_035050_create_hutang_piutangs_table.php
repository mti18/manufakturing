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
        Schema::create('hutang_piutangs', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('uuid')->unique();

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')
                  ->onDelete('cascade');

            $table->bigInteger('profile_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles')
                  ->onDelete('cascade');

            $table->bigInteger('salesorder_id')->unsigned()->nullable();
            $table->foreign('salesorder_id')->references('id')->on('sales_orders')
                   ->onDelete('cascade');
      
            $table->bigInteger('pembelian_id')->unsigned()->nullable();
            $table->foreign('pembelian_id')->references('id')->on('pembelians')
                  ->onDelete('cascade');
      
              
            $table->integer('tempo')->default(0);
            $table->date('tanggal');
            $table->decimal('jumlah', 13, 2);
            $table->enum('type', ['Hutang', 'Piutang'])->default('Utang');
            $table->string('keterangan')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hutang_piutangs');
    }
};
