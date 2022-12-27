<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Helpers\RestApi;
use App\Models\AssetJurnal;
use App\Models\MasterJurnal;
use App\Models\ReportJurnal;
use Validator, DB;
use Illuminate\Http\Request;


class PenyusutanController extends Controller
{
    public function paginate(Request $request)
    {
        if (request()->wantsJson()) {

            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @angka=0+' . $page * $per));
            $pengajuan = AssetJurnal::with(['PenyusutanFirst', 'Penyusutan', 'Penyusutan.MasterJurnal', 'Penyusutan.MasterJurnal.jurnal_item'])->where(function ($q) use ($request) {
                $q->where('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('nm_asset', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);

            $bulan = date('m');
            $tahun = date('Y');
            $tanggal = date('t', strtotime($tahun . "-" . ($bulan - 1)));
            $bulan -= 1;

            $pengajuan->map(function ($a) use ($tanggal, $tahun, $bulan) {

                $nilai_buku = $a->price;
                $susut = 0;

                foreach ($a->Penyusutan as $item) {
                    $item->masterjurnal->jurnal_item->sum('debit');
                    $nilai_buku -= $item->masterjurnal->jurnal_item->sum('debit');
                    $susut += $item->masterjurnal->jurnal_item->sum('debit');
                }


                if (empty($a->PenyusutanFirst)) {
                    $a->action = '
                    <button class="btn btn-sm btn-icon btn-light-info mb-2 susutkan" title="Susutkan" data-id="' . $a->uuid . '"><i class="fa fa-gavel"></i></button>';
                    return $a;
                } else if (strtotime($a->PenyusutanFirst->tanggal) < strtotime($tahun . "-" . $bulan . "-" . $tanggal)) {
                    $a->action = '
                    <button class="btn btn-sm btn-icon btn-light-info mb-2 susutkan" title="Susutkan" data-id="' . $a->uuid . '"><i class="fa fa-gavel"></i></button>';
                    $a->PenyusutanFirst->tanggal = AppHelper::tanggal_indo($a->PenyusutanFirst->tanggal);
                    $a->nilai_buku = AppHelper::rupiah($nilai_buku);
                    $a->susut = AppHelper::rupiah($susut);
                    $a->price = AppHelper::rupiah($a->price);
                    return $a;
                } else {

                    $a->PenyusutanFirst->tanggal = AppHelper::tanggal_indo($a->PenyusutanFirst->tanggal);
                    $a->nilai_buku = AppHelper::rupiah($nilai_buku);
                    $a->susut = AppHelper::rupiah($susut);
                    $a->price = AppHelper::rupiah($a->price);
                }

                if ($a->price == $a->susut &&  !empty($a->PenyusutanFirst)) {
                    $a->action = ' <button class="btn btn-sm btn-icon btn-light-danger mb-2" title="Tidak bisa disusutkan lagi" data-id="' . $a->uuid . '"><i class="fa fa-minus"></i></button>';
                } else {
                    $a->action = ' <button class="btn btn-sm btn-icon btn-light-success mb-2" title="Sudah disusutkan" data-id="' . $a->uuid . '"><i class="fa fa-check-square"></i></button>';
                }

                return $a;
            });


            return response()->json($pengajuan, 200);
        }
    }

    public function susutkan(Request $request)
    {
        if (request()->wantsJson()) {
            $rules = [
                 'id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return RestApi::error($validator->messages()->first(), 400);
            }


            $asset = AssetJurnal::with(['Golongan', 'PenyusutanFirst'])->where('id', $request->id)->first();

            if (empty($asset->PenyusutanFirst)) {
                $exp = explode(' ', $asset->number);
                $tahun = $exp[1];
                $bulanint = AppHelper::stringbulantoint($exp[0]);
            } else {
                $bulanint = date('m', strtotime($asset->PenyusutanFirst->tanggal)) + 1;
                $tahun = date('Y', strtotime($asset->PenyusutanFirst->tanggal));
            }
            $rate_per_bulan = $asset->golongan->rate / 100 / 12 / $asset->Golongan->masa;
            $price_per_bulan = $asset->price * $rate_per_bulan;



            $date = date("Y-m-d");
            $timeStart = strtotime($tahun . "-" . $bulanint);
            $timeEnd = strtotime("$date");
            // Menambah bulan ini + semua bulan pada tahun sebelumnya
            $numBulan = (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
            // menghitung selisih bulan
            $numBulan += date("m", $timeEnd) - date("m", $timeStart);

            $check = ReportJurnal::where('tahun', '>=',date("Y", $timeStart))->first();

            if(!empty($check)){
                return RestApi::error('Periode Telah di tutup, hapus penutupan periode terlebuh dahulu ditahun '.date("Y", $timeStart));
            }
            
            $arr = [];
            
            $nilai_buku = $asset->price;

            foreach ($asset->Penyusutan as $item) {
                $item->masterjurnal->masterjurnal_item->sum('debit');
                $nilai_buku -= $item->masterjurnal->jurnal_item->sum('debit');
            }

            $bulan = date("m", $timeStart);
            $year = date("Y", $timeStart);
            for ($i = 0; $i < $numBulan; $i++) {

                if ($nilai_buku - $price_per_bulan < 0) {
                    break;
                }


                array_push($arr, $year . '-' . $bulan . '-' . date("t", strtotime($year . '-' . $bulan)));

                $masterjurnal = MasterJurnal::create([
                    'kd_jurnal' => "JP-" . $year . $bulan . date("t", strtotime($year . '-' . $bulan)) . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'tanggal' => $year . '-' . $bulan . '-' . date("t", strtotime($year . '-' . $bulan)),
                    'type' => 'penyesuaian',
                ]);



                $masterjurnal->jurnal_item()->create([
                    'account_id' => $asset->beban_id,
                    'debit' => $price_per_bulan,
                    'kredit' => 0,
                    'keterangan' => 'Penyusutan ' . $asset->nm_asset,
                ]);
                $masterjurnal->jurnal_item()->create([
                    'account_id' => $asset->penyusutan_id,
                    'debit' => 0,
                    'keterangan' => 'Penyusutan ' . $asset->nm_asset,
                    'kredit' => $price_per_bulan,
                ]);

                $masterjurnal->penyusutan()->create([
                    'asset_id' => $asset->id,
                    'tanggal' => $year . '-' . $bulan . '-' . date("t", strtotime($year . '-' . $bulan)),
                ]);


                $bulan += 1;
                if ($bulan > 12) {
                    $bulan = 1;
                    $year += 1;
                }
            }


            return RestApi::success($bulan);
        }
    }
}
