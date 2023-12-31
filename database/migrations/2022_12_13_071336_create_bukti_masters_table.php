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
        Schema::create('bukti_masters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->integer('masterjurnal_id')->unsigned();
            $table->foreign('masterjurnal_id')->references('id')->on('master_jurnals')->onDelete('cascade');

            $table->string('file');
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
        Schema::dropIfExists('bukti_masters');
    }
};
