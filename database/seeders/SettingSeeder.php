<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        Setting::create([
            'name' => 'Base MTI',
            'background' => 'assets/media/misc/bg-auth.jpg',
            'description' => 'lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
            'logo_dark' => 'assets/media/logos/logo-1-dark.svg',
            'logo_light' => 'assets/media/logos/logo-1.svg',
        ]);
    }
}
