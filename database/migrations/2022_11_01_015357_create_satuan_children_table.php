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
        Schema::create('satuan_children', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->string('nm_satuan_children');

            $table->bigInteger('barangsatuan_id')->unsigned();
            $table->foreign('barangsatuan_id')->references('id')->on('barang_satuans')->onDelete('cascade');

            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('satuan_children')->onDelete('cascade');

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
        Schema::dropIfExists('satuan_children');
    }
};
