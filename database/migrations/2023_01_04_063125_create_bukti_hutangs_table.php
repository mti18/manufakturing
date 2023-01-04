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
        Schema::create('bukti_hutangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->integer('hutangpiutang_id')->unsigned();
            $table->foreign('hutangpiutang_id')->references('id')->on('hutang_piutangs')->onDelete('cascade');

            $table->string('bukti');
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
        Schema::dropIfExists('bukti_hutangs');
    }
};
