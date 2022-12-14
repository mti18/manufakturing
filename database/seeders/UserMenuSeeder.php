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
            // ['user_group_id' => 1, 'menu_id' => 2],
            ['user_group_id' => 1, 'menu_id' => 3],
            ['user_group_id' => 1, 'menu_id' => 4],
            ['user_group_id' => 1, 'menu_id' => 5],
            ['user_group_id' => 1, 'menu_id' => 6],
            ['user_group_id' => 1, 'menu_id' => 7],
            ['user_group_id' => 1, 'menu_id' => 8],
            ['user_group_id' => 1, 'menu_id' => 9],
            ['user_group_id' => 1, 'menu_id' => 10],
            ['user_group_id' => 1, 'menu_id' => 11],
            ['user_group_id' => 1, 'menu_id' => 12],
            ['user_group_id' => 1, 'menu_id' => 13],
            ['user_group_id' => 1, 'menu_id' => 14],
            ['user_group_id' => 1, 'menu_id' => 15],
            ['user_group_id' => 1, 'menu_id' => 16],
            ['user_group_id' => 1, 'menu_id' => 17],
            ['user_group_id' => 1, 'menu_id' => 18],
            ['user_group_id' => 1, 'menu_id' => 19],
            ['user_group_id' => 1, 'menu_id' => 20],
            ['user_group_id' => 1, 'menu_id' => 21],
            ['user_group_id' => 1, 'menu_id' => 22],
            ['user_group_id' => 1, 'menu_id' => 23],
            ['user_group_id' => 1, 'menu_id' => 24],
            ['user_group_id' => 1, 'menu_id' => 25],
            ['user_group_id' => 1, 'menu_id' => 26],
            ['user_group_id' => 1, 'menu_id' => 27],
            ['user_group_id' => 1, 'menu_id' => 28],
            ['user_group_id' => 1, 'menu_id' => 29],
            ['user_group_id' => 1, 'menu_id' => 30],
            ['user_group_id' => 1, 'menu_id' => 31],
            ['user_group_id' => 1, 'menu_id' => 32],
            ['user_group_id' => 1, 'menu_id' => 33],
            ['user_group_id' => 1, 'menu_id' => 34],
            ['user_group_id' => 1, 'menu_id' => 35],
            ['user_group_id' => 1, 'menu_id' => 36],
            ['user_group_id' => 1, 'menu_id' => 37],
            ['user_group_id' => 1, 'menu_id' => 38],
            ['user_group_id' => 1, 'menu_id' => 39],
            ['user_group_id' => 1, 'menu_id' => 40],
            ['user_group_id' => 1, 'menu_id' => 41],
            ['user_group_id' => 1, 'menu_id' => 42],
        ];

        foreach($usermenus as $usermenu){
           UserMenu::create($usermenu);
        }

    }
}
