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
        Schema::create('report_jurnals', function (Blueprint $table) {
            $table->increments('id');
            
            $table->double('bayar', 13, 2);
            $table->double('kurang_bayar', 13, 2);
            $table->double('total_pajak', 13, 2);


            $table->string('tahun');

            $table->integer('jurnal_id')->unsigned();
            $table->foreign("jurnal_id")->references('id')->on('jurnals')
                ->onDelete('cascade');

            $table->integer('account_id')->unsigned();
            $table->foreign("account_id")->references('id')->on('accounts')
                ->onDelete('cascade');

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
        Schema::dropIfExists('report_jurnals');
    }
};
