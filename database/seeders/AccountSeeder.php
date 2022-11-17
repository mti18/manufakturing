<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('$account')->delete();

        $account = [
            ['id' => 52, 'nm_account' => 'Asset', 'kode_account' => '1', 'account_type' => 'rill', 'type' => 'debit', 'parent_id' => 'NULL'],
            ['id' => 55, 'nm_account' => 'Kas', 'kode_account' => '1.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '52'],
            ['id' => 56, 'nm_account' => 'Kas Besar', 'kode_account' => '1.1.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '55'],
            ['id' => 57, 'nm_account' => 'Kas Kecil', 'kode_account' => '1.1.2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '55'],
            ['id' => 62, 'nm_account' => 'Piutang', 'kode_account' => 'l.2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '52'],
            ['id' => 63, 'nm_account' => 'Asset Tetap', 'kode_account' => '1.3', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '52'],
            ['id' => 64, 'nm_account' => 'Kewajiban', 'kode_account' => '2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => 'NULL'],
            ['id' => 68, 'nm_account' => 'Asset Lancar', 'kode_account' => '1.4', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '52'],
            ['id' => 71, 'nm_account' => 'Modal', 'kode_account' => '3', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => 'NULL'],
            ['id' => 78, 'nm_account' => 'Pendapatan', 'kode_account' => '4', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => 'NULL'],
            ['id' => 79, 'nm_account' => 'Hasil Jual', 'kode_account' => '4.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '78'],
            ['id' => 80, 'nm_account' => 'Biaya', 'kode_account' => '5', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => 'NULL'],
            ['id' => 88, 'nm_account' => 'Asset Lainnya', 'kode_account' => '1.5', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '52'],
            ['id' => 89, 'nm_account' => 'Pengeluaran', 'kode_account' => '5.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '80'],
            ['id' => 90, 'nm_account' => 'Biaya Produksi', 'kode_account' => '5.2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '80'],
            ['id' => 91, 'nm_account' => 'Biaya Lainnya', 'kode_account' => '5.3', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '80'],
            ['id' => 92, 'nm_account' => 'Biaya Penyusutan Asset', 'kode_account' => '5.4', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '80'],
            ['id' => 93, 'nm_account' => 'Hutang', 'kode_account' => '2.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '64'],
            ['id' => 94, 'nm_account' => 'Pajak', 'kode_account' => '2.2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '64'],
            ['id' => 95, 'nm_account' => 'Hutang Bank', 'kode_account' => '2.1.1', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '93'],
            ['id' => 96, 'nm_account' => 'Hutang Usaha', 'kode_account' => '2.1.2', 'account_type' => 'nominal', 'type' => 'debit', 'parent_id' => '93'],

        ];

        foreach ($account as $account) {
            Account::create($account);
        }
    }
}
