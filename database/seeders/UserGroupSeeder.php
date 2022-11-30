<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserGroup;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_groups')->delete();
        UserGroup::create(['id' => 1, 'name' => 'Administrator']);
        UserGroup::create(['id' => 2, 'name' => 'User']);
        UserGroup::create(['id' => 3, 'name' => 'Admin Gudang']);
        UserGroup::create(['id' => 4, 'name' => 'Admin Marketing']);
        UserGroup::create(['id' => 5, 'name' => 'Admin Keuangan']);
        UserGroup::create(['id' => 6, 'name' => 'Admin Pembelian']);
        UserGroup::create(['id' => 7, 'name' => 'Admin Suplier']);
    }
}
