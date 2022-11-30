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
        Schema::create('jurnal_items', function (Blueprint $table) {
          
            $table->increments('id');
            $table->uuid('uuid')->unique();


            $table->integer('masterjurnal_id')->unsigned();
            $table->foreign('masterjurnal_id')->references('id')->on('master_jurnals')->onDelete('cascade');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->double('debit', 13, 2)->default(0);
            $table->double('kredit', 13, 2)->default(0);


            $table->text("keterangan")->nullable();

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
        Schema::dropIfExists('jurnal_items');
    }
};
