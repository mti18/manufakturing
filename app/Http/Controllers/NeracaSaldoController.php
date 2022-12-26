<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class NeracaSaldoController extends Controller
{
    public function neraca($bulan, $tahun, $type)
    {

        $data = Account::with(['jurnal_item'  => function ($q) use ($bulan, $tahun, $type) {
            $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun, $type) {
                if ($type == 0) {
                    $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                } else {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
                }
            });
        }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun, $type) {
            if ($type == 0) {
                $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            } else {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
            }
        })->get();

        $data = $data->sortBy('kode_account');

        $data->map(function ($a) use ($type) {
            $a->sum = $a->jurnal_item->sum('debit') - $a->jurnal_item->sum('kredit');

            return $a;
        });

        return response()->json($data);
    }

    // public function reportneraca ($bulan, $tahun, $type)
    // {
        
    //     $data =
    // }

}
