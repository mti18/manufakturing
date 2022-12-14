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
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('uuid')->unique();

            $table->string('nm_account');
            $table->string('kode_account');

            $table->enum('account_type', ['nominal', 'rill']);
            $table->enum('type', ['debit', 'kredit']);


            $table->Integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('restrict');



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
        Schema::dropIfExists('accounts');
    }
};
