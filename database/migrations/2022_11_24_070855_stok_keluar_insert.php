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
            CREATE TRIGGER stok_keluar_insert
                BEFORE INSERT ON `stok_keluars`
                FOR EACH ROW
                BEGIN
                     IF NEW.tipe_barang = "barang_mentah" THEN
                        UPDATE `barang_mentahs` SET `stok` = stok - NEW.barang_keluar, 
                        `updated_at` = NOW()
                        WHERE `id` = NEW.barangmentah_id;
                    ELSE
                        IF NEW.kualitas = "bagus" THEN
                            UPDATE `barang_jadis` SET `stok_bagus` = stok_bagus - NEW.barang_keluar,
                            `updated_at` = NOW()
                            WHERE `id` = NEW.barangjadi_id;
                        ELSE
                            UPDATE `barang_jadis` SET `stok_jelek` = stok_jelek - NEW.barang_keluar,
                            `updated_at` = NOW()
                            WHERE `id` = NEW.barangjadi_id;
                        END IF;
                    END IF;
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
        DB::unprepared('DROP TRIGGER stok_keluar_insert');
    }
};
