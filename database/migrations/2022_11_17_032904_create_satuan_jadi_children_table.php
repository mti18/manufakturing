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
        Schema::create('satuan_jadi_children', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nm_satuan_jadi_children');

            $table->bigInteger('barangsatuanjadi_id')->unsigned();
            $table->foreign('barangsatuanjadi_id')->references('id')->on('barang_satuan_jadis')->onDelete('cascade');

            $table->bigInteger('nilai');

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
        Schema::dropIfExists('satuan_jadi_children');
    }
};
