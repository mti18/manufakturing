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
        Schema::table('asset_jurnals', function (Blueprint $table) {
            $table->integer('masterjurnal_id')->unsigned()->nullable()->after("nm_asset");

            $table->foreign("masterjurnal_id")->references('id')->on('master_jurnals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_jurnals', function (Blueprint $table) {
            //
        });
    }
};
