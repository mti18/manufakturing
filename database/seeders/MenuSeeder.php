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
            ['id' => 1, 'name' => 'Login', 'url' => '/login', 'route' => 'login', 'component' => 'auth/Login'],

            // Front
            ['auth' => true, 'level' => ['admin'], 'children' => [
                ['id' => 2, 'name' => 'Dashboard', 'url' => '/', 'route' => 'dashboard', 'component' => 'Index', 'icon' => 'las la-home fs-2'],
                ['id' => 3, 'name' => 'Master', 'route' => 'dashboard.master', 'icon' => 'las fa-database fs-2', 'children' => [
                    ['id' => 4, 'name' => 'User', 'url' => '/master/user', 'route' => 'dashboard.master.user', 'icon' => 'las la-user fs-2', 'component' => 'master/user/Index'],
                    ['id' => 5, 'name' => 'User Grup', 'url' => '/master/user-group', 'route' => 'dashboard.master.user-group', 'icon' => 'las la-users fs-2', 'component' => 'master/user-group/Index'],
                    ['id' => 6, 'name' => 'Jabatan', 'url' => '/master/jabatan', 'route' => 'dashboard.master.jabatan', 'icon' => 'las la-user-tie fs-2', 'component' => 'master/jabatan/Index'],
                    ['id' => 7, 'name' => 'Setting', 'route' => 'dashboard.master.setting', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 8, 'name' => 'Website', 'url' => '/master/setting/website', 'route' => 'dashboard.master.setting.website', 'icon' => 'las la-laptop fs-2', 'component' => 'master/user/Index'],
                    ]],
                    ['id' => 9, 'name' => 'Barang', 'route' => 'dashboard.master.barang', 'icon' => 'fas fa-box-open', 'children' => [
                        ['id' => 10, 'name' => 'Satuan', 'url' => '/master/barang/barangsatuan', 'route' => 'dashboard.master.barang.barangsatuan', 'icon' => 'asdasd', 'component' => 'master/barang/barangsatuan/Index'],
                        ['id' => 11, 'name' => 'Satuan Jadi', 'url' => '/master/barang/barangsatuanjadi', 'route' => 'dashboard.master.barang.barangsatuanjadi', 'icon' => 'dasdsad', 'component' => 'master/barang/barangsatuanjadi/Index'],
                        ['id' => 13, 'name' => 'Barang Mentah', 'url' => '/master/barang/barangmentah', 'route' => 'dashboard.master.barang.barangmentah', 'icon' => 'cvbn', 'component' => 'master/barang/barangmentah/Index'],
                        ['id' => 14, 'name' => 'Kategori', 'url' => '/master/barang/kategori', 'route' => 'dashboard.master.barang.kategori', 'icon' => 'aeaf', 'component' => 'master/barang/kategori/Index'],
                        ['id' => 15, 'name' => 'Barang Jadi', 'url' => '/master/barang/barangjadi', 'route' => 'dashboard.master.barang.barangjadi', 'icon' => 'poiu', 'component' => 'master/barang/barangjadi/Index'],
                        ['id' => 16, 'name' => 'Gudang', 'url' => '/master/barang/gudang', 'route' => 'dashboard.master.barang.gudang', 'icon' => 'pojh', 'component' => 'master/barang/gudang/Index'],
                        ['id' => 17, 'name' => 'Barang Produksi', 'url' => '/master/barang/barangproduksi', 'route' => 'dashboard.master.barang.barangproduksi', 'icon' => 'pojh', 'component' => 'master/barang/barangproduksi/Index'],
                    ]], 
                    ['id' => 18, 'name' => 'Stok Barang', 'route' => 'dashboard.master.stokbarang', 'icon' => 'fas fa-warehouse', 'children' => [
                        ['id' => 19, 'name' => 'Stok Masuk', 'url' => '/master/barang/stokmasuk', 'route' => 'dashboard.master.barang.stokmasuk', 'icon' => 'pojh', 'component' => 'master/barang/stokmasuk/Index'],
                        ['id' => 20, 'name' => 'Stok Keluar', 'url' => '/master/barang/stokkeluar', 'route' => 'dashboard.master.barang.stokkeluar', 'icon' => 'pojh', 'component' => 'master/barang/stokkeluar/Index'],
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
