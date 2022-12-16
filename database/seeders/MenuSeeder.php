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
            ['id' => 1, 'name' => 'Login', 'url' => '/login',   'route' => 'login', 'component' => 'auth/Login'],
            
            // Front
            ['auth' => true, 'level' => ['admin', 'user'], 'children' => [
                
                ['id' => 2, 'name' => 'Ganti Password', 'url' => '/gantipassword', 'route' => 'updatepw', 'component' => 'gantipassword/Index'],
                ['id' => 3, 'name' => 'Dashboard', 'url' => '/', 'route' => 'dashboard', 'component' => 'Index', 'icon' => 'las la-home fs-2'],
                ['id' => 4, 'name' => 'Sales Order', 'url' => '/salesorder', 'route' => 'dashboard.salesorder', 'component' => 'salesorder/Index', 'icon' => 'fas fa-shopping-cart fs-2'],
                ['id' => 5, 'name' => 'Konfirmasi Order', 'url' => '/konfirmasiorder', 'route' => 'dashboard.konfirmasiorder', 'component' => 'konfirmasiorder/Index', 'icon' => 'fas fa-clipboard-check fs-2'],
                ['id' => 6, 'name' => 'Pembelian', 'url' => '/pembelian', 'route' => 'dashboard.pembelian', 'component' => 'pembelian/Index', 'icon' => 'fas fa-shopping-bascket fs-2'],
                ['id' => 7, 'name' => 'Master', 'route' => 'dashboard.master', 'icon' => 'las fa-database fs-2', 'children' => [
                    ['id' => 8, 'name' => 'User', 'url' => '/master/user', 'route' => 'dashboard.master.user', 'icon' => 'las la-user fs-2', 'component' => 'master/user/Index'],
                    ['id' => 9, 'name' => 'User Grup', 'url' => '/master/user-group', 'route' => 'dashboard.master.user-group', 'icon' => 'las la-users fs-2', 'component' => 'master/user-group/Index'],
                    ['id' => 10, 'name' => 'Menu', 'url' => '/master/menu', 'route' => 'dashboard.master.menu', 'icon' => 'fas fa-grip-horizontal fs-2', 'component' => 'master/menu/Index'],
                    ['id' => 11, 'name' => 'Jabatan', 'url' => '/master/jabatan', 'route' => 'dashboard.master.jabatan', 'icon' => 'las la-user-tie fs-2', 'component' => 'master/jabatan/Index'],
                    ['id' => 12, 'name' => 'Setting', 'route' => 'dashboard.master.setting', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 13, 'name' => 'Website', 'url' => '/master/setting/website', 'route' => 'dashboard.master.setting.website', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/user/Index'],
                    ]],
                    ['id' => 14, 'name' => 'Barang', 'route' => 'dashboard.master.barang', 'icon' => 'fas fa-box-open', 'children' => [
                        ['id' => 15, 'name' => 'Satuan', 'url' => '/master/barang/barangsatuan', 'route' => 'dashboard.master.barang.barangsatuan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangsatuan/Index'],
                        ['id' => 16, 'name' => 'Satuan Jadi', 'url' => '/master/barang/barangsatuanjadi', 'route' => 'dashboard.master.barang.barangsatuanjadi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangsatuanjadi/Index'],
                        ['id' => 17, 'name' => 'Barang Mentah', 'url' => '/master/barang/barangmentah', 'route' => 'dashboard.master.barang.barangmentah', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangmentah/Index'],
                        ['id' => 18, 'name' => 'Kategori', 'url' => '/master/barang/kategori', 'route' => 'dashboard.master.barang.kategori', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/kategori/Index'],
                        ['id' => 19, 'name' => 'Barang Jadi', 'url' => '/master/barang/barangjadi', 'route' => 'dashboard.master.barang.barangjadi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangjadi/Index'],
                        ['id' => 20, 'name' => 'Gudang', 'url' => '/master/barang/gudang', 'route' => 'dashboard.master.barang.gudang', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/gudang/Index'],
                        ['id' => 21, 'name' => 'Barang Produksi', 'url' => '/master/barang/barangproduksi', 'route' => 'dashboard.master.barang.barangproduksi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangproduksi/Index'],
                    ]], 
                    ['id' => 22, 'name' => 'Stok Barang', 'route' => 'dashboard.master.stokbarang', 'icon' => 'fas fa-warehouse', 'children' => [
                        ['id' => 23, 'name' => 'Stok Masuk', 'url' => '/master/stokbarang/stokmasuk', 'route' => 'dashboard.master.stokbarang.stokmasuk', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/stokbarang/stokmasuk/Index'],
                        ['id' => 24, 'name' => 'Stok Keluar', 'url' => '/master/stokbarang/stokkeluar', 'route' => 'dashboard.master.stokbarang.stokkeluar', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/stokbarang/stokkeluar/Index'],
                        ]],
                    ['id' => 25, 'name' => 'Suppliers', 'route' => 'dashboard.master.suppliers', 'icon' => 'fas fa-truck', 'children' => [
                        ['id' => 26, 'name' => 'Supplier', 'url' => '/master/suppliers/supplier', 'route' => 'dashboard.master.suppliers.supplier', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/suppliers/supplier/Index'],
                        ['id' => 27, 'name' => 'Customer', 'url' => '/master/suppliers/customer', 'route' => 'dashboard.master.suppliers.customer', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/suppliers/customer/Index'],
                    ]],
                    ['id' => 28, 'name' => 'Wilayah', 'route' => 'dashboard.master.wilayah', 'icon' => 'fas fa-map', 'children' => [
                        ['id' => 29, 'name' => 'Provinsi', 'url' => '/master/wilayah/provinsi', 'route' => 'dashboard.master.wilayah.provinsi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/provinsi/Index'],
                        ['id' => 30, 'name' => 'Kota', 'url' => '/master/suppliers/kota', 'route' => 'dashboard.master.wilayah.kota', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kota/Index'],
                        ['id' => 31, 'name' => 'Kecamatan', 'url' => '/master/suppliers/kecamatan', 'route' => 'dashboard.master.wilayah.kecamatan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kecamatan/Index'],
                        ['id' => 32, 'name' => 'Kelurahan', 'url' => '/master/suppliers/kelurahan', 'route' => 'dashboard.master.wilayah.kelurahan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kelurahan/Index'],
                    ]],
                    ['id' => 33, 'name' => 'Keuangan', 'route' => 'dashboard.master.keuangan', 'icon' => 'fas fa-money-bill', 'children' => [
                        ['id' => 34, 'name' => 'Kelompok', 'url' => '/master/keuangan/kelompok', 'route' => 'dashboard.master.keuangan.kelompok', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/kelompok/Index'],
                        ['id' => 35, 'name' => 'Assets', 'url' => '/master/keuangan/assets', 'route' => 'dashboard.master.keuangan.assets', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/assets/Index'],
                        ['id' => 36, 'name' => 'Jenis Asset', 'url' => '/master/keuangan/jenisasset', 'route' => 'dashboard.master.keuangan.jenisasset', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/jenisasset/Index'],
                    ]],
                    ['id' => 37, 'name' => 'Accounts', 'route' => 'dashboard.master.accounts', 'icon' => 'fas fa-broadcast-tower', 'children' => [
                        ['id' => 38, 'name' => 'Account', 'url' => '/master/accounts/account', 'route' => 'dashboard.master.accounts.account', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/account/user/Index'],
                    ]],
                ]],
                ['id' => 39, 'name' => 'Setting', 'route' => 'dashboard.setting', 'icon' => 'fas fa-hdd fs-2', 'children' => [
                    ['id' => 40, 'name' => 'Config', 'route' => 'dashboard.setting.config', 'icon' => 'las fa-cog fs-3', 'children' => [
                        ['id' => 41, 'name' => 'Profile', 'url' => '/setting/config/profile', 'route' => 'dashboard.setting.config.profile', 'icon' => 'fas fa-circle fa-xs', 'component' => 'setting/config/profile/Index'],
                        ],
                    ],
                ],
                ],
                ['id' => 42, 'name' => 'Jurnal Laporan', 'route' => 'dashboard.master.jurnallaporan', 'icon' => 'fas fa-book fs-2', 'children' => [
                        ['id' => 43, 'name' => 'Master Jurnal', 'url' => '/master/jurnallaporan/masterjurnal', 'route' => 'dashboard.master.jurnallaporan.masterjurnal', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/master_jurnal/Index'],
                    ],
                ],
                ['id' => 44, 'name' => 'Pembelian', 'url' => '/pembelian', 'route' => 'dashboard.pembelian', 'component' => 'pembelian/Index', 'icon' => 'fas fa-clipboard-check fs-2'],
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
