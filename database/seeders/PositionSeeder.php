<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Position;

class PositionSeeder extends Seeder
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
            ['id' => 1, 'code' => 'CEO-1', 'name' => 'Chief Executive Officer (CEO)'],
            ['id' => 2, 'code' => 'KDI-2', 'name' => 'Kepala Divisi Implementator'],
            ['id' => 3, 'code' => 'KUX-3', 'name' => 'Kepala User UI/UX'],
            ['id' => 4, 'code' => 'SD-4', 'name' => 'Software Developer'],
            ['id' => 5, 'code' => 'DA-5', 'name' => 'Database Administrator'],
            ['id' => 6, 'code' => 'SA-6', 'name' => 'System Analyst'],
            ['id' => 7, 'code' => 'FIA-7', 'name' => 'Finance and Accounting'],
        ];

        foreach ($data as $value) {
            Position::create($value);
        }
    }
}
