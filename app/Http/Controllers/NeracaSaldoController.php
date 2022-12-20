<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class NeracaSaldoController extends Controller
{
    public function neraca($bulan, $tahun, $tipe)
    {

        $data = Account::with(['jurnal_item'  => function ($q) use ($bulan, $tahun, $tipe) {
            $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun, $tipe) {
                if ($tipe == 0) {
                    $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                } else {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
                }
            });
        }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun, $tipe) {
            if ($tipe == 0) {
                $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            } else {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
            }
        })->get();

        $data = $data->sortBy('kode_account');

        $data->map(function ($a) use ($tipe) {
            $a->sum = $a->jurnal_item->sum('debit') - $a->jurnal_item->sum('kredit');

            return $a;
        });

        return response()->json($data);
    }

}
