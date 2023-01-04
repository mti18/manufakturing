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
        Schema::create('hutang_piutang_details', function (Blueprint $table) {
            $table->increments('id');
            
            $table->uuid('uuid')->unique();

            $table->integer('hutangpiutang_id')->unsigned();
            $table->foreign('hutangpiutang_id')->references('id')->on('hutang_piutangs')
                  ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');

            $table->date('tanggal');
            $table->string('bukti')->nullable();
            $table->decimal('jumlah', 13, 2);
            $table->string('keterangan')->nullable();

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
        Schema::dropIfExists('hutang_piutang_details');
    }
};
