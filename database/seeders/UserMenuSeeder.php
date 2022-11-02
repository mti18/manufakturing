<?php

namespace Database\Seeders;

use App\Models\UserMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_menus')->delete();

        $usermenus = [
            ['id' => 16,'user_group_id' => 1, 'menu_id' => 2],
            ['id' => 17,'user_group_id' => 1, 'menu_id' => 3],
            ['id' => 18,'user_group_id' => 1, 'menu_id' => 4],
            ['id' => 19,'user_group_id' => 1, 'menu_id' => 5],
            ['id' => 20,'user_group_id' => 1, 'menu_id' => 6],
            ['id' => 21,'user_group_id' => 1, 'menu_id' => 7],
            ['id' => 22,'user_group_id' => 1, 'menu_id' => 8],
            ['id' => 23,'user_group_id' => 1, 'menu_id' => 10],
            ['id' => 24,'user_group_id' => 1, 'menu_id' => 9],
            ['id' => 27,'user_group_id' => 1, 'menu_id' => 11],
            ['id' => 30,'user_group_id' => 1, 'menu_id' => 12],
            ['id' => 31,'user_group_id' => 1, 'menu_id' => 13],
        ];

        foreach($usermenus as $usermenu){
           UserMenu::create($usermenu);
        }

    }
}
