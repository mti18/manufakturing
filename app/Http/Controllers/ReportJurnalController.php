<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Helpers\RestApi;
use App\Models\Account;
use App\Models\MasterJurnal;
use App\Models\ReportJurnal;
use Illuminate\Http\Request;

class ReportJurnalController extends Controller
{
    public function tahun()
    {
        $data = ReportJurnal::orderBy('tahun', 'asc')->first();

        return response()->json($data);
    }
    public function check(Request $request)
    {
        $data = ReportJurnal::where('tahun', $request->tahun)->first();

        if (empty($data)) {
            return response()->json(["data" => "belum tutup buku silahkan tutup buku", "status" => false]);
        }

        return response()->json(["data" => "sudah tutup buku silahkan tutup buku", "status" => true]);
    }

    public function checkTambah($tahun)
    {
        $data = ReportJurnal::where('tahun', $tahun)->first();

        return response()->json($data);
    }


    public function pratutup(Request $request)
    {
        $tahun = intval($request->data['tahun']);



        $data = MasterJurnal::where('tanggal', '<=', "$tahun-12-" . date("t", (strtotime("$tahun-12"))))->where('status', "0")->orderBy("tanggal", "ASC")->first();

        if(empty($data)){
            return RestApi::error('jurnal tidak ada di tahun '.$tahun.' dan dibawahnya', 400);
        }

        $tahunarr = [];

        for ($i = intval(date("Y", strtotime($data->tanggal))); $i <= $tahun; $i++) {
            array_push($tahunarr, intval($i));
        }
        return $tahunarr;
    }

    public function labarugi(Request $request)
    {
        $tahun = $request->tahun;

        sort($tahun);

        $arrRes = [];


        foreach ($tahun as $parent) {

            $data = Account::with(['umum' => function ($q) use ($parent) {
                $q->whereHas("MasterJurnal", function ($q) use ($parent) {
                    $q->whereYear("tanggal", $parent);
                });
            }, 'penyesuaian' => function ($q) use ($parent) {
                $q->whereHas("MasterJurnal", function ($q) use ($parent) {
                    $q->whereYear("tanggal", $parent);
                });
            }, 'Account_id'])->get();


            $nom_kredit = [];
            $nom_debit = [];



            foreach ($data as $item) {
                //neraca saldo            
                $total = 0;
                foreach ($item->umum as $umum) {
                    $total += $umum->debit - $umum->kredit;
                }
                //jurnal penyesuaian
                $total_jps = 0;
                foreach ($item->penyesuaian as $penyesuaian) {
                    $total_jps += $penyesuaian->debit - $penyesuaian->kredit;
                }
                //NSD
                $total_nsd = $total + $total_jps;

                if ($item->Account_id->tipe != "rill") {
                    if ($item->Account_id->amount == "debit") {
                        $item->total = abs($total_nsd);
                        array_push($nom_debit, $item);
                    } else {
                        $item->total = abs($total_nsd);
                        array_push($nom_kredit, $item);
                    }
                }
            }

            $debit = 0;
            foreach ($nom_debit as $item) {
                $debit += $item->total;
            }

            $kredit = 0;
            foreach ($nom_kredit as $item) {
                $kredit += $item->total;
            }

            if (($debit - $kredit) < 0) {

                array_push($arrRes, [
                    "data_debit" => $nom_debit,
                    "data_kredit" => $nom_kredit,
                    "nom" => $debit - $kredit,
                    "nom_bulat" => AppHelper::rupiahNoRp(round(abs($debit - $kredit) / 10000) * 10000),
                    "nom_pajak" =>  0,
                    "nom_potong" =>  0,
                    "nom_belum_bayar" =>  0,
                    "nom_sudah_bayar" =>  0,
                    "tahun" => $parent

                ]);
            } else {

                array_push($arrRes, [
                    "data_debit" => $nom_debit,
                    "data_kredit" => $nom_kredit,
                    "nom" => $debit - $kredit,
                    "nom_bulat" => AppHelper::rupiahNoRp(round(abs($debit - $kredit) / 10000) * 10000),
                    "nom_pajak" =>  AppHelper::rupiahNoRp((round(abs($debit - $kredit) / 10000) * 10000) * 0.125),
                    "nom_potong" =>  AppHelper::rupiahNoRp((round(abs($debit - $kredit) / 10000) * 10000)  - ((round(abs($debit - $kredit) / 10000) * 10000) * 0.125)),
                    "nom_belum_bayar" =>  AppHelper::rupiahNoRp((round(abs($debit - $kredit) / 10000) * 10000) * 0.125),
                    "nom_sudah_bayar" =>  0,
                    "tahun" => $parent

                ]);
            }
        }


        return response()->json($arrRes);
    }

    public function tutuphapus(Request $request)
    {
        $data = ReportJurnal::with('masterjurnal', 'masterjurnal.jurnal_item')->where('tahun', '>=', $request->form)->get();

        foreach ($data as $item) {
            foreach ($item->masterjurnal->jurnal_item as $citem) {
                $citem->delete();
            }
            MasterJurnal::whereYear('tanggal', $item->tahun)->update(['status' => '0']);
            $item->masterjurnal->delete();
        }
        return RestApi::success(['pesan' => 'sukses']);
    }

    public function tutup(Request $request)
    {

        foreach ($request->data as $item) {
            $data = MasterJurnal::create([
                'kd_jurnal' => "JP-" . $item['tahun'] . 12 . date("t", strtotime($item['tahun'] . '-12')) . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'tanggal' => $item['tahun'] . '-12-' . date("t", strtotime($item['tahun'] . '-12')),
                'type' => 'penyesuaian',
            ]);

            $data->jurnal_item()->create([
                "account_id" => $request->form["pajak"],
                "debit" => intval(str_replace('.', '', $item['nom_pajak'])),
                "kredit" => 0,
                "keterangan" => "membayar pajak"
            ]);

            $data->jurnal_item()->create([
                "account_id" => $request->form["bank"],
                "kredit" => intval(str_replace('.', '', $item['nom_pajak'])),
                "debit" => 0,
                "keterangan" => "membayar pajak " . $item['tahun']
            ]);

            $data->ReportJurnal()->create([
                'bayar' => intval(str_replace('.', '', $item['nom_sudah_bayar'])),
                'kurang_bayar' => intval(str_replace('.', '', $item['nom_belum_bayar'])),
                'total_pajak' => intval(str_replace('.', '', $item['nom_pajak'])),
                'tahun' => $item['tahun'],
                'account_id' => $request->form['account_id']
            ]);

            $oi = MasterJurnal::whereYear('tanggal', $item['tahun']);
            $oi->update(['status' => "1"]);
        }

        return RestApi::success(['message' => 'sukses menutup periode']);
    }
}
