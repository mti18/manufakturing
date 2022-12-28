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
        Schema::create('penyusutans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            $table->bigInteger('asset_id')->unsigned();
            $table->foreign('asset_id')->references('id')->on('asset_jurnals')
                ->onDelete('cascade');

            $table->integer('masterjurnal_id')->unsigned();
            $table->foreign('masterjurnal_id')->references('id')->on('master_jurnals')
                ->onDelete('cascade');

            $table->date('tanggal');

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
        Schema::dropIfExists('penyusutans');
    }
};
