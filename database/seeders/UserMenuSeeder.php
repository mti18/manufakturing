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
            ['id' => 1,'user_group_id' => 1, 'menu_id' => 2],
            ['id' => 2,'user_group_id' => 1, 'menu_id' => 3],
            ['id' => 3,'user_group_id' => 1, 'menu_id' => 4],
            ['id' => 4,'user_group_id' => 1, 'menu_id' => 15],
            ['id' => 5,'user_group_id' => 1, 'menu_id' => 16],
            ['id' => 6,'user_group_id' => 1, 'menu_id' => 17],
            ['id' => 7,'user_group_id' => 1, 'menu_id' => 18],
            ['id' => 8,'user_group_id' => 1, 'menu_id' => 19],
            ['id' => 9,'user_group_id' => 1, 'menu_id' => 9],
            ['id' => 10,'user_group_id' => 1, 'menu_id' => 10],
            ['id' => 11,'user_group_id' => 1, 'menu_id' => 11],
            ['id' => 12,'user_group_id' => 1, 'menu_id' => 12],
            ['id' => 13,'user_group_id' => 1, 'menu_id' => 13],
            ['id' => 14,'user_group_id' => 1, 'menu_id' => 14],
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
            ['id' => 31,'user_group_id' => 1, 'menu_id' => 13],
            ['id' => 32,'user_group_id' => 1, 'menu_id' => 14],
            ['id' => 33,'user_group_id' => 1, 'menu_id' => 15],
            ['id' => 34,'user_group_id' => 1, 'menu_id' => 16],
        ];

        foreach($usermenus as $usermenu){
           UserMenu::create($usermenu);
        }

    }
}
