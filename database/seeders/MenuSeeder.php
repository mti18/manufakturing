<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->delete();

        $menus = [
            // Auth
            ['name' => 'Login', 'url' => '/login', 'route' => 'login', 'component' => 'auth/Login'],

            // Front
            ['auth' => true, 'level' => ['admin'], 'children' => [
                ['name' => 'Dashboard', 'url' => '/', 'route' => 'dashboard', 'component' => 'Index', 'icon' => 'las la-home fs-2'],
                ['name' => 'Master', 'route' => 'dashboard.master', 'icon' => 'las fa-database fs-2', 'children' => [
                    ['name' => 'User', 'url' => '/master/user', 'route' => 'dashboard.master.user', 'icon' => 'las la-user fs-2', 'component' => 'master/user/Index'],
                    ['name' => 'User Grup', 'url' => '/master/user-group', 'route' => 'dashboard.master.user-group', 'icon' => 'las la-users fs-2', 'component' => 'master/user-group/Index'],
                    ['name' => 'Jabatan', 'url' => '/master/jabatan', 'route' => 'dashboard.master.jabatan', 'icon' => 'las la-user-tie fs-2', 'component' => 'master/jabatan/Index'],
                    ['name' => 'Setting', 'route' => 'dashboard.master.setting', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['name' => 'Website', 'url' => '/master/setting/website', 'route' => 'dashboard.master.setting.website', 'icon' => 'las la-laptop fs-2', 'component' => 'master/user/Index'],
                    ]],
                    ['name' => 'Barang', 'route' => 'dashboard.master.barang', 'icon' => 'fas fa-box-open', 'children' => [
                        ['name' => 'Satuan', 'url' => '/master/barang/barangsatuan', 'route' => 'dashboard.master.barang.barangsatuan', 'icon' => 'asdasd', 'component' => 'master/barang/barangsatuan/Index'],
                        ['name' => 'Satuan Jadi', 'url' => '/master/barang/barangsatuanjadi', 'route' => 'dashboard.master.barang.barangsatuanjadi', 'icon' => 'dasdsad', 'component' => 'master/barang/barangsatuanjadi/Index'],
                        ['name' => 'Barang Mentah', 'url' => '/master/barang/barangmentah', 'route' => 'dashboard.master.barang.barangmentah', 'icon' => 'cvbn', 'component' => 'master/barang/barangmentah/Index'],
                        ['name' => 'Kategori', 'url' => '/master/barang/kategori', 'route' => 'dashboard.master.barang.kategori', 'icon' => 'aeaf', 'component' => 'master/barang/kategori/Index'],
                    ]],
                ]],
            ]]
        ];

        foreach ($menus as $menu) {
            if (!isset($menu['name'])) {
                $this->seedChildren($menu['children'], 0, $menu['auth'], $menu['level']);
            } else {
                $data = Menu::create(collect($menu)->except(['children'])->toArray());
                if (isset($menu['children'])) {
                    @$this->seedChildren($menu['children'], $data->id, $menu['auth'], $menu['level']);
                }
            }
        }
    }

    private function seedChildren($menus, $parent_id, $parent_auth, $parent_level)
    {
        foreach ($menus as $menu) {
            if (!isset($menu['name'])) {
                $this->seedChildren($menu['children'], $parent_id, $parent_auth, $parent_level);
            } else {
                $menu['parent_id'] = $parent_id;
                $menu['auth'] = $parent_auth;
                $menu['level'] = $parent_level;
                $data = Menu::create(collect($menu)->except(['children'])->toArray());
                if (isset($menu['children'])) {
                    $this->seedChildren($menu['children'], $data->id, $data->auth, $data->level);
                }
            }
        }
    }
}
