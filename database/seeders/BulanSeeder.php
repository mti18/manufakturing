<?php

namespace Database\Seeders;

use App\Models\Bulan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bulan::create(['bulan' => 'Januari']);
        Bulan::create(['bulan' => 'Februari']);
        Bulan::create(['bulan' => 'Maret']);
        Bulan::create(['bulan' => 'April']);
        Bulan::create(['bulan' => 'Mei']);
        Bulan::create(['bulan' => 'Juni']);
        Bulan::create(['bulan' => 'Juli']);
        Bulan::create(['bulan' => 'Agustus']);
        Bulan::create(['bulan' => 'September']);
        Bulan::create(['bulan' => 'Oktober']);
        Bulan::create(['bulan' => 'November']);
        Bulan::create(['bulan' => 'Desember']);
    }
}
