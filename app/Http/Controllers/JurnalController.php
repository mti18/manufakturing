<?php

namespace App\Http\Controllers;

use App\Models\MasterJurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function jurnal($bulan, $tahun, $type)
    {
        if ($type == 0) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'umum')->orderBy('tanggal')->get();
        } else if ($type == 1) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penyesuaian')->orderBy('tanggal')->get();
        } else {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penutup')->orderBy('tanggal')->get();
        }
        return response()->json($data);
    }
}
