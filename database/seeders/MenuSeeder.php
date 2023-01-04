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
                
                ['id' => 2, 'name' => 'Ganti Password', 'url' => '/gantipassword', 'route' => 'updatepw', 'component' => 'gantipassword/Index', 'shown' => 0],
                ['id' => 3, 'name' => 'Dashboard', 'url' => '/', 'route' => 'dashboard', 'component' => 'Index', 'icon' => 'las la-home fs-2'],
                ['id' => 4, 'name' => 'Sales Order', 'url' => '/salesorder', 'route' => 'dashboard.salesorder', 'component' => 'salesorder/Index', 'icon' => 'fas fa-shopping-cart fs-2'],
                ['id' => 5, 'name' => 'Konfirmasi Order', 'url' => '/konfirmasiorder', 'route' => 'dashboard.konfirmasiorder', 'component' => 'konfirmasiorder/Index', 'icon' => 'fas fa-clipboard-check fs-2'],
                ['id' => 6, 'name' => 'Pembelian', 'url' => '/pembelian', 'route' => 'dashboard.pembelian', 'component' => 'pembelian/Index', 'icon' => 'fas fa-shopping-bag fs-2'],
                ['id' => 7, 'name' => 'Permintaan', 'url' => '/permintaan', 'route' => 'dashboard.permintaan', 'component' => 'permintaan/Index', 'icon' => 'fas fa-shopping-basket fs-2'],
                ['id' => 8, 'name' => 'Pembelian Internal', 'url' => '/pembelianinternal', 'route' => 'dashboard.pembelianinternal', 'component' => 'pembelianinternal/Index', 'icon' => 'fas fa-shopping-basket fs-2'],
                ['id' => 65, 'name' => 'Hutang Piutang', 'url' => '/hutangpiutang', 'route' => 'dashboard.hutangpiutang', 'component' => 'hutangpiutang/Index', 'icon' => 'fas fa-money-bill-wave fs-2'],
                ['id' => 9, 'name' => 'Pembayaran', 'url' => '/pembayaran', 'route' => 'dashboard.pembayaran', 'component' => 'pembayaran/Index', 'icon' => 'fas fa-money-bill-wave fs-2'],
                ['id' => 10, 'name' => 'Master', 'route' => 'dashboard.master', 'icon' => 'las fa-database fs-2', 'children' => [
                    ['id' => 14, 'name' => 'Jabatan', 'url' => '/master/jabatan', 'route' => 'dashboard.master.jabatan', 'icon' => 'las la-user-tie fs-2', 'component' => 'master/jabatan/Index'],
                    ['id' => 15, 'name' => 'Setting', 'route' => 'dashboard.master.setting', 'icon' => 'las fa-cog fs-2', 'children' => [
                        ['id' => 16, 'name' => 'Website', 'url' => '/master/setting/website', 'route' => 'dashboard.master.setting.website', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/user/Index'],
                    ]],
                    ['id' => 17, 'name' => 'Barang', 'route' => 'dashboard.master.barang', 'icon' => 'fas fa-box-open', 'children' => [
                        ['id' => 18, 'name' => 'Satuan', 'url' => '/master/barang/barangsatuan', 'route' => 'dashboard.master.barang.barangsatuan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangsatuan/Index'],
                        ['id' => 19, 'name' => 'Satuan Jadi', 'url' => '/master/barang/barangsatuanjadi', 'route' => 'dashboard.master.barang.barangsatuanjadi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangsatuanjadi/Index'],
                        ['id' => 20, 'name' => 'Barang Mentah', 'url' => '/master/barang/barangmentah', 'route' => 'dashboard.master.barang.barangmentah', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangmentah/Index'],
                        ['id' => 21, 'name' => 'Kategori', 'url' => '/master/barang/kategori', 'route' => 'dashboard.master.barang.kategori', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/kategori/Index'],
                        ['id' => 22, 'name' => 'Barang Jadi', 'url' => '/master/barang/barangjadi', 'route' => 'dashboard.master.barang.barangjadi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangjadi/Index'],
                        ['id' => 23, 'name' => 'Gudang', 'url' => '/master/barang/gudang', 'route' => 'dashboard.master.barang.gudang', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/gudang/Index'],
                        ['id' => 24, 'name' => 'Barang Produksi', 'url' => '/master/barang/barangproduksi', 'route' => 'dashboard.master.barang.barangproduksi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/barang/barangproduksi/Index'],
                    ]], 
                    ['id' => 25, 'name' => 'Stok Barang', 'route' => 'dashboard.master.stokbarang', 'icon' => 'fas fa-warehouse', 'children' => [
                        ['id' => 26, 'name' => 'Stok Masuk', 'url' => '/master/stokbarang/stokmasuk', 'route' => 'dashboard.master.stokbarang.stokmasuk', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/stokbarang/stokmasuk/Index'],
                        ['id' => 27, 'name' => 'Stok Keluar', 'url' => '/master/stokbarang/stokkeluar', 'route' => 'dashboard.master.stokbarang.stokkeluar', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/stokbarang/stokkeluar/Index'],
                        ]],
                    ['id' => 28, 'name' => 'Suppliers', 'route' => 'dashboard.master.suppliers', 'icon' => 'fas fa-truck', 'children' => [
                        ['id' => 29, 'name' => 'Supplier', 'url' => '/master/suppliers/supplier', 'route' => 'dashboard.master.suppliers.supplier', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/suppliers/supplier/Index'],
                        ['id' => 30, 'name' => 'Customer', 'url' => '/master/suppliers/customer', 'route' => 'dashboard.master.suppliers.customer', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/suppliers/customer/Index'],
                    ]],
                    ['id' => 31, 'name' => 'Wilayah', 'route' => 'dashboard.master.wilayah', 'icon' => 'fas fa-map', 'children' => [
                        ['id' => 32, 'name' => 'Provinsi', 'url' => '/master/wilayah/provinsi', 'route' => 'dashboard.master.wilayah.provinsi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/provinsi/Index'],
                        ['id' => 33, 'name' => 'Kota', 'url' => '/master/suppliers/kota', 'route' => 'dashboard.master.wilayah.kota', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kota/Index'],
                        ['id' => 34, 'name' => 'Kecamatan', 'url' => '/master/suppliers/kecamatan', 'route' => 'dashboard.master.wilayah.kecamatan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kecamatan/Index'],
                        ['id' => 35, 'name' => 'Kelurahan', 'url' => '/master/suppliers/kelurahan', 'route' => 'dashboard.master.wilayah.kelurahan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/wilayah/kelurahan/Index'],
                    ]],
                    ['id' => 36, 'name' => 'Keuangan', 'route' => 'dashboard.master.keuangan', 'icon' => 'fas fa-money-bill', 'children' => [
                        ['id' => 37, 'name' => 'Kelompok', 'url' => '/master/keuangan/kelompok', 'route' => 'dashboard.master.keuangan.kelompok', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/kelompok/Index'],
                        ['id' => 38, 'name' => 'Assets', 'url' => '/master/keuangan/assets', 'route' => 'dashboard.master.keuangan.assets', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/assets/Index'],
                        ['id' => 39, 'name' => 'Jenis Asset', 'url' => '/master/keuangan/jenisasset', 'route' => 'dashboard.master.keuangan.jenisasset', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/keuangan/jenisasset/Index'],
                    ]],
                    ['id' => 40, 'name' => 'Accounts', 'route' => 'dashboard.master.accounts', 'icon' => 'fas fa-broadcast-tower', 'children' => [
                        ['id' => 41, 'name' => 'Account', 'url' => '/master/accounts/account', 'route' => 'dashboard.master.accounts.account', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/account/user/Index'],
                    ]],
                ]],
                ['id' => 42, 'name' => 'Setting', 'route' => 'dashboard.setting', 'icon' => 'fas fa-hdd fs-2', 'children' => [
                    ['id' => 11, 'name' => 'User', 'url' => '/master/user', 'route' => 'dashboard.master.user', 'icon' => 'las la-user fs-2', 'component' => 'master/user/Index'],
                    ['id' => 12, 'name' => 'User Grup', 'url' => '/master/user-group', 'route' => 'dashboard.master.user-group', 'icon' => 'las la-users fs-2', 'component' => 'master/user-group/Index'],
                    ['id' => 13, 'name' => 'Menu', 'url' => '/master/menu', 'route' => 'dashboard.master.menu', 'icon' => 'fas fa-grip-horizontal fs-2', 'component' => 'master/menu/Index'],
                    ['id' => 43, 'name' => 'Config', 'route' => 'dashboard.setting.config', 'icon' => 'las fa-cog fs-3', 'children' => [
                        ['id' => 44, 'name' => 'Profile', 'url' => '/setting/config/profile', 'route' => 'dashboard.setting.config.profile', 'icon' => 'fas fa-circle fa-xs', 'component' => 'setting/config/profile/Index'],
                        ],
                    ],
                ],
                ],
                ['id' => 45, 'name' => 'Jurnal Laporan', 'route' => 'dashboard.master.jurnallaporan', 'icon' => 'fas fa-book fs-2', 'children' => [
                    ['id' => 46, 'name' => 'Keuangan', 'route' => 'dashboard.master.jurnallaporan.keuangan', 'icon' => 'fas fa-circle fa-xs', 'children' => [
                        ['id' => 47, 'name' => 'Laporan', 'url' => '/master/jurnallaporan/keuangan/laporan', 'route' => 'dashboard.master.jurnallaporan.keuangan.laporan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/laporan/Index'],
                        ['id' => 48, 'name' => 'Master Jurnal', 'url' => '/master/jurnallaporan/masterjurnal', 'route' => 'dashboard.master.jurnallaporan.masterjurnal', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/master_jurnal/Index'],
                        ['id' => 49, 'name' => 'Buku Besar', 'url' => '/master/jurnallaporan/bukubesar', 'route' => 'dashboard.master.jurnallaporan.bukubesar', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/bukubesar/Index'],
                        ['id' => 50, 'name' => 'Neraca Saldo', 'url' => '/master/jurnallaporan/neracasaldo', 'route' => 'dashboard.master.jurnallaporan.neracasaldo', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/neracasaldo/Index'],
                        ['id' => 51, 'name' => 'Jurnal', 'url' => '/master/jurnallaporan/jurnal', 'route' => 'dashboard.master.jurnallaporan.jurnal', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/jurnal/Index'],
                        ['id' => 52, 'name' => 'Assets', 'route' => 'dashboard.master.jurnallaporan.keuangan.assets', 'icon' => 'fas fa-circle fa-xs', 'children' => [
                            ['id' => 53, 'name' => 'Golongan', 'url' => '/master/jurnallaporan/keuangan/assets/golongan', 'route' => 'dashboard.master.jurnallaporan.keuangan.assets.golongan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/assets/golongan/Index'],
                            ['id' => 54, 'name' => 'Asset Jurnal', 'url' => '/master/jurnallaporan/keuangan/assets/asset', 'route' => 'dashboard.master.jurnallaporan.keuangan.assets.asset', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/assets/asset/Index'],
                            ['id' => 55, 'name' => 'Asset Group', 'url' => '/master/jurnallaporan/keuangan/assets/assetgroup', 'route' => 'dashboard.master.jurnallaporan.keuangan.assets.assetgroup.', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/assets/asset_group/Index'],
                            ['id' => 56, 'name' => 'Penyusuran', 'url' => '/master/jurnallaporan/keuangan/assets/penyusutan', 'route' => 'dashboard.master.jurnallaporan.keuangan.assets.penyusutan.', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/assets/penyusutan/Index'],
                            ]],
                        ['id' => 57, 'name' => 'WorkSheet', 'url' => '/master/jurnallaporan/keuangan/worksheet', 'route' => 'dashboard.master.jurnallaporan.keuangan.worksheet', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/worksheet/Index'],
                    ]],
                    ['id' => 58, 'name' => 'Pembelian', 'route' => 'dashboard.master.jurnallaporan.pembelian', 'icon' => 'fas fa-circle fa-xs', 'children' => [
                            ['id' => 59, 'name' => 'Laporan', 'url' => '/master/jurnallaporan/pembelian/laporan', 'route' => 'dashboard.master.jurnallaporan.pembelian.laporan', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/pembelian/Index'],
                        ],
                    ],
                    ['id' => 60, 'name' => 'Stok Barang', 'route' => 'dashboard.master.jurnallaporan.stokbarang', 'icon' => 'fas fa-circle fa-xs', 'children' => [
                            ['id' => 61, 'name' => 'Laporan Barang Jadi', 'url' => '/master/jurnallaporan/stokbarang/laporanbarangjadi', 'route' => 'dashboard.master.jurnallaporan.stokbarang.laporanbarangjadi', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/stokbarang/stockjadi/Index'],
                            ['id' => 62, 'name' => 'Laporan Barang Mentah', 'url' => '/master/jurnallaporan/stokbarang/laporanbarangmentah', 'route' => 'dashboard.master.jurnallaporan.stokbarang.laporanbarangmentah', 'icon' => 'fas fa-circle fa-xs', 'component' => 'master/jurnallaporan/stokbarang/stockmentah/Index'],
                        ],
                    ],
                    ],
                ],
                ['id' => 63, 'name' => 'Konfirmasi Pimpinan', 'route' => 'dashboard.konfirmasipimpinan', 'icon' => 'fas fa-crown fs-2', 'children' => [
                        ['id' => 64, 'name' => 'Order (SO)', 'url' => '/master/konfirmasipimpinan/order', 'route' => 'dashboard.konfirmasipimpinan.order', 'icon' => 'fas fa-circle fa-xs', 'component' => 'konfirmasipimpinan/order/Index'],
                    ],
                ],
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
