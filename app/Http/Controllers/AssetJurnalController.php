<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use App\Models\AssetJurnal;
use App\Models\MasterJurnal;
use Validator, DB;
use Illuminate\Http\Request;

class AssetJurnalController extends Controller
{
    public function paginate(Request $request)
    {
        if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            // Get User By Search And Per Page
            DB::statement(DB::raw('set @angka=0+' . $page * $per));
            $asset = AssetJurnal::where(function ($q) use ($request) {
                $q->where('nm_asset', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('price', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('number', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'asc')->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);

            // Add Columns
            $asset->map(function ($a) {
                $a->price = AppHelper::rupiah($a->price, 2);
                $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="' . $a->uuid . '"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Hapus" data-id="' . $a->uuid . '"><i class="fa fa-trash"></i></button>';

                return $a;
            });
            return response()->json($asset);
        } else {
            abort(404);
        }
    }
    public function store(Request $request)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $validator = Validator::make($request->all(), [
                'nm_asset'  => 'required|string',
                'number'  => 'required|string',
                'price'  => 'required|string',
                'golongan_id'  => 'required|integer',
                'asset_group_id'  => 'required|integer',
                'akun_id'  => 'required|integer',
                'beban_id'  => 'required|integer',
                'penyusutan_id'  => 'required|integer',
                'kredit_id'  => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => $validator->messages()->first()
                ], 400);
            }

            $request['price'] = str_replace('.', '', $request['price']);
            $request['price'] = str_replace(',', '.', $request['price']);

            $exp = explode(' ', $request['number']);

            $masterjurnal = MasterJurnal::create([
                'kd_jurnal' => 'JU-' . date('Ymt', strtotime($exp[1] . "-" . AppHelper::stringbulantoint($exp[0]))) . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                "tanggal" => $exp[1] . "-" . AppHelper::stringbulantoint($exp[0]) . "-" . date("t", strtotime($exp[1] . "-" . AppHelper::stringbulantoint($exp[0]))),
            ]);

            $acc1 = Account::where('id', $request['akun_id'])->first();
            $acc2 = Account::where('id', $request['kredit_id'])->first();

            $masterjurnal->jurnal_item()->create([
                'account_id' => $request['akun_id'],
                'debit' => $request['price'],
                'kredit' => 0,
                'keterangan' => "membeli Asset - " . $request['nm_asset'],
                'before_amount' => $acc2->saldo_berjalan,
                'after_amount' => $acc2->saldo_berjalan + $request['price'],
            ]);

            $masterjurnal->jurnal_item()->create([
                'account_id' => $request['kredit_id'],
                'keterangan' => "membeli Asset - " . $request['nm_asset'],
                'debit' => 0,
                'kredit' => $request['price'],
                'before_amount' => $acc2->saldo_berjalan,
                'after_amount' => $acc2->saldo_berjalan - $request['price'],
            ]);


            $masterjurnal->Asset()->create($request->all());

            if ($masterjurnal) {
                return response()->json([
                    'status' => true,
                    'data' => 'Berhasil menambah data.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menambah data.'
                ], 500);
            }
        } else {
            abort(404);
        }
    }

    public function show()
    {
        if (request()->wantsJson() && request()->ajax()) {
            $asset = AssetJurnal::get();

            return response()->json([
                'status'    => true,
                'data'      => $asset
            ], 200);
        } else {
            abort(404);
        }
    }

    public function getData($uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $asset = AssetJurnal::findByUuid($uuid);

            return response()->json([
                'status'    => true,
                'data'      => $asset
            ], 200);
        } else {
            abort(404);
        }
    }

    public function edit($uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $asset = AssetJurnal::findByUuid($uuid);
            $asset->price = number_format($asset->price, 2, '.', '');

            return response()->json([
                'status'    => true,
                'data'      => $asset
            ], 200);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $asset = AssetJurnal::findByUuid($uuid);

            $validator = Validator::make($request->all(), [
                'nm_asset'  => 'required|string',
                'number'  => 'required|string',
                'price'  => 'required|string',
                'golongan_id'  => 'required|integer',
                'asset_group_id'  => 'required|integer',
                'akun_id'  => 'required|integer',
                'beban_id'  => 'required|integer',
                'penyusutan_id'  => 'required|integer',
            ]);

            $request['price'] = str_replace('.', '', $request['price']);
            $request['price'] = str_replace(',', '.', $request['price']);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages()->first()
                ], 400);
            }

            $asset->update($request->all());

            if ($asset) {
                return response()->json([
                    'status' => true,
                    'data' => 'Berhasil merubah data.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal merubah data.'
                ], 500);
            }
        } else {
            abort(404);
        }
    }

    public function destroy($uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $asset = AssetJurnal::with(['MasterJurnal', "MasterJurnal.jurnal_item", 'Penyusutan'])->where('uuid', $uuid)->first();

            if (!$asset) {
                return response()->json([
                    'status' => false,
                    'message' => 'data tidak ada'
                ], 400);
            }


            foreach ($asset->MasterJurnal->jurnal_item as $item) {
                $item->delete();
            }

            if (count($asset->Penyusutan) > 0) {
                foreach ($asset->Penyusutan as $kitem) {
                    foreach ($kitem->MasterJurnal->jurnal_item as $citem) {
                        $citem->delete();
                    }
                    $kitem->MasterJurnal->delete();
                    $item->delete();
                }
            }
            $asset->delete();



            if ($asset) {
                return response()->json([
                    'status' => true,
                    'data' => 'Berhasil menghapus data '
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus data.'
                ], 500);
            }
        } else {
            abort(404);
        }
    }

    public function RekapPenyusutan()
    {
        $date = date("Y-m-d");
        $timeStart = strtotime("2020-7");
        $timeEnd = strtotime("$date");
        // Menambah bulan ini + semua bulan pada tahun sebelumnya
        $numBulan = (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
        // menghitung selisih bulan
        $numBulan += date("m", $timeEnd) - date("m", $timeStart);


        $bulan = date("m", $timeStart);
        $year = date("Y", $timeStart);
        for ($i = 0; $i < $numBulan; $i++) {

            echo $year . '-' . $bulan . '-' . date("t", strtotime($year . '-' . $bulan)) . '<br>';
            $bulan += 1;
            if ($bulan > 12) {
                $bulan = 1;
                $year += 1;
            }
        }
    }

}
