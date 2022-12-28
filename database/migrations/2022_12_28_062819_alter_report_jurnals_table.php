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
        Schema::table('report_jurnals', function (Blueprint $table) {
            $table->integer('masterjurnal_id')->unsigned();
            $table->foreign("masterjurnal_id")->references('id')->on('master_jurnals')
                ->onDelete('cascade');

            $table->dropForeign(['jurnal_id']);
            $table->dropColumn('jurnal_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_jurnals', function (Blueprint $table) {
            $table->dropForeign(['masterjurnal_id']);
            $table->dropColumn('masterjurnal_id');

            $table->integer('jurnal_id')->unsigned();
            $table->foreign("jurnal_id")->references('id')->on('jurnals')
                ->onDelete('cascade');
        });
    }
};
