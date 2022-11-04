<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BarangSatuan;

class BarangSatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->delete();

        $data = [
            ['id' => 1, 'nm_satuan' => 'Meter', 'satuan' => 'm'],
            ['id' => 2, 'nm_satuan' => 'Liter', 'satuan' => 'l'],
            ['id' => 3, 'nm_satuan' => 'Gram', 'satuan' => 'g'],

        ];

        foreach ($data as $value) {
            BarangSatuan::create($value);
        }
    }
}
