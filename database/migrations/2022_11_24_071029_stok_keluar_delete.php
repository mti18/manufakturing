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
            CREATE TRIGGER stok_keluar_delete
                BEFORE DELETE ON `stok_keluars`
                FOR EACH ROW
                BEGIN
                    IF OLD.tipe_barang = "barang_mentah" THEN
                        UPDATE `barang_mentahs` SET `stok` = `stok` + OLD.barang_keluar,
                        `updated_at` = NOW()
                        WHERE `id` = OLD.barangmentah_id;
                    ELSE
                        IF OLD.kualitas = "bagus" THEN
                            UPDATE `barang_jadis` SET `stok_bagus` = stok_bagus + OLD.barang_keluar,
                            `updated_at` = NOW()
                            WHERE `id` = OLD.barangjadi_id;
                        ELSE
                            UPDATE `barang_jadis` SET `stok_jelek` = stok_jelek + OLD.barang_keluar,
                            `updated_at` = NOW()
                            WHERE `id` = OLD.barangjadi_id;
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
        DB::unprepared('DROP TRIGGER `stok_keluar_delete`');
    }
};
