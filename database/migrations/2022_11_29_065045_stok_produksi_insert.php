<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::unprepared('
            CREATE TRIGGER stok_produksi_insert
            BEFORE INSERT ON `stok_produksis`
            FOR EARCH ROW 
            BEGIN
                UPDATE `barang_jadis` SET `stok_bagus` = stok_bagus + NEW.stok_jadi,
                `updated_at` = NOW()
                WHERE `id` = NEW.barangjadi_id;

                UPDATE `barang_mentahs` SET `stok` = stok - NEW.stok_digunakan,
                `updated_at` = NOW()
                WHERE `id` = NEW.barang_mentah_id;
            END
        ');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER stok_produksi_insert');
    }
};
