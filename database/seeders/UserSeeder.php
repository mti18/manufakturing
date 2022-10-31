<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mcflyon.co.id',
            'password' => bcrypt('12345678'),
            'nip' => '12345678',
            'whatsapp' => '089644829853',
            'level' => 'admin',
            'status' => 'tetap',
            'is_active' => true,
            'user_group_id' => 1,
            'position_id' => 1,
        ]);
    }
}
