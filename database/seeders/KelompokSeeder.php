<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelompok;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelompoks')->delete();

        $kelompoks = [
            ['id' => 1, 'nama' => 'Mesin dan Peralatan'],
            ['id' => 2, 'nama' => 'Kendaraan'],
            ['id' => 3, 'nama' => 'Inventaris'],
            ['id' => 4, 'nama' => 'Bangunan'],
            ['id' => 5, 'nama' => 'Tanah'],

        ];

        foreach ($kelompoks as $kelompok) {
            Kelompok::create($kelompok);
        }
    }
}
