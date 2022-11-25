<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserMenu;

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
            ['id' => 37,'user_group_id' => 1, 'menu_id' => 2],
            ['id' => 38,'user_group_id' => 1, 'menu_id' => 3],
            ['id' => 39,'user_group_id' => 1, 'menu_id' => 4],
            ['id' => 40,'user_group_id' => 1, 'menu_id' => 5],
            ['id' => 41,'user_group_id' => 1, 'menu_id' => 6],
            ['id' => 42,'user_group_id' => 1, 'menu_id' => 7],
            ['id' => 43,'user_group_id' => 1, 'menu_id' => 8],
            ['id' => 44,'user_group_id' => 1, 'menu_id' => 10],
            ['id' => 45,'user_group_id' => 1, 'menu_id' => 9],
            ['id' => 46,'user_group_id' => 1, 'menu_id' => 11],
            ['id' => 47,'user_group_id' => 1, 'menu_id' => 13],
            ['id' => 48,'user_group_id' => 1, 'menu_id' => 14],
            ['id' => 49,'user_group_id' => 1, 'menu_id' => 15],
            ['id' => 50,'user_group_id' => 1, 'menu_id' => 16],
            ['id' => 51,'user_group_id' => 1, 'menu_id' => 17],
            ['id' => 52,'user_group_id' => 1, 'menu_id' => 19],
            ['id' => 53,'user_group_id' => 1, 'menu_id' => 18],
            ['id' => 55,'user_group_id' => 1, 'menu_id' => 20],
         
        
        ];

        foreach($usermenus as $usermenu){
           UserMenu::create($usermenu);
        }

    }
}
