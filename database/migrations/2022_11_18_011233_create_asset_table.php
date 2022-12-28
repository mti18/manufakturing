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
        Schema::create('asset', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();

            $table->string('nm_asset');
            $table->string('number');
            $table->double('price', 13, 2)->default(0);

            $table->integer('golongan_id')->unsigned();
            $table->foreign('golongan_id')->references('id')->on('golongans')
                ->onDelete('restrict');

            $table->integer('asset_group_id')->unsigned();
            $table->foreign('asset_group_id')->references('id')->on('asset_groups')
                ->onDelete('restrict');

            $table->integer('akumulasi_id')->unsigned();
            $table->foreign('akumulasi_id')->references('id')->on('acounts')
                ->onDelete('restrict');

            $table->integer('beban_id')->unsigned();
            $table->foreign('beban_id')->references('id')->on('acounts')
                ->onDelete('restrict');

            $table->integer('akun_id')->unsigned();
            $table->foreign('akun_id')->references('id')->on('acounts')
                ->onDelete('restrict');


            $table->integer('kredit_id')->unsigned();
            $table->foreign('kredit_id')->references('id')->on('acounts')
                ->onDelete('restrict');

                  

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
        Schema::dropIfExists('asset');
    }
};
