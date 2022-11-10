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
                    ['id' => 9, 'name' => 'Suppliers', 'route' => 'dashboard.master.suppliers', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 10, 'name' => 'Supplier', 'url' => '/master/suppliers/supplier', 'route' => 'dashboard.master.suppliers.supplier', 'icon' => 'las la-laptop fs-2', 'component' => 'master/suppliers/supplier/Index'],
                        ['id' => 11, 'name' => 'Customer', 'url' => '/master/suppliers/customer', 'route' => 'dashboard.master.suppliers.customer', 'icon' => 'las la-laptop fs-2', 'component' => 'master/suppliers/customer/Index'],
                    ]],
                    ['id' => 12, 'name' => 'Stok Barang', 'route' => 'dashboard.master.stokbarang', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 13, 'name' => 'Stok Barang Masuk', 'url' => '/master/stokbarang/stokmasuk', 'route' => 'dashboard.master.stokbarang.stokmasuk', 'icon' => 'las la-laptop fs-2', 'component' => 'master/stokbarang/stokmasuk/Index'],
                        ['id' => 14, 'name' => 'Stok Barang Keluar', 'url' => '/master/stokbarang/stokkeluar', 'route' => 'dashboard.master.stokbarang.stokkeluar', 'icon' => 'las la-laptop fs-2', 'component' => 'master/stokbarang/stokkeluar/Index'],
                    ]],
                    ['id' => 15, 'name' => 'Wilayah', 'route' => 'dashboard.master.wilayah', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 16, 'name' => 'Provinsi', 'url' => '/master/wilayah/provinsi', 'route' => 'dashboard.master.wilayah.provinsi', 'icon' => 'las la-laptop fs-2', 'component' => 'master/wilayah/provinsi/Index'],
                        ['id' => 17, 'name' => 'Kota', 'url' => '/master/wilayah/kota', 'route' => 'dashboard.master.wilayah.kota', 'icon' => 'las la-laptop fs-2', 'component' => 'master/wilayah/kota/Index'],
                        ['id' => 18, 'name' => 'Kecamatan', 'url' => '/master/wilayah/kecamatan', 'route' => 'dashboard.master.wilayah.kecamatan', 'icon' => 'las la-laptop fs-2', 'component' => 'master/wilayah/kecamatan/Index'],
                        ['id' => 19, 'name' => 'Kelurahan', 'url' => '/master/wilayah/kelurahan', 'route' => 'dashboard.master.wilayah.kelurahan', 'icon' => 'las la-laptop fs-2', 'component' => 'master/wilayah/kelurahan/Index'],
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
