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


            $table->integer('jurnal_id')->unsigned();
            $table->foreign('jurnal_id')->references('id')->on('jurnals')->onDelete('cascade');

            $table->integer('acount_id')->unsigned();
            $table->foreign('acount_id')->references('id')->on('acounts')->onDelete('cascade');

            $table->double('debit', 13, 2)->default(0);
            $table->double('kredit', 13, 2)->default(0);

            $table->double('before_amount', 13, 2);
            $table->double('after_amount', 13, 2);


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
