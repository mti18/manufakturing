<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use App\Models\BuktiMaster;
use App\Models\MasterJurnal;
// use App\Models\Bulan;
use App\Models\JurnalItem;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJurnalController extends Controller
{
    public function paginate(Request $request, $bulan, $tahun) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = MasterJurnal::with(['jurnal_item','Penyusutan'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where(function ($q) use ($request) {
                $q->where('tanggal', 'LIKE', '%' . $request->search . '%');
            })->orderBy('tanggal', 'asc')->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);

            // Add Columns
            $courses->map(function ($a) {
                $a->tanggal = AppHelper::tanggal_indo($a->tanggal);
                $a->debit = AppHelper::rupiah($a->jurnal_item->where('kredit', 0)->sum('debit'));
                $a->kredit = AppHelper::rupiah($a->jurnal_item->where('debit', 0)->sum('kredit'));

                if ($a->status == "1") {
                    $a->action = '<button class="btn btn-sm btn-icon btn-light-info mb-2 view" title="Lihat" data-id="' . $a->uuid . '"><i class="fa fa-eye"></i></button>';
                } else {

                    if (empty($a->Penyusutan)) {
                        $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="' . $a->uuid . '"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Hapus" data-id="' . $a->uuid . '"><i class="fa fa-trash"></i></button> <button class="btn btn-sm btn-icon btn-light-info mb-2 view" title="Lihat" data-id="' . $a->uuid . '"><i class="fa fa-eye"></i></button>';
                    } else {
                        $a->action = '<button class="btn btn-sm btn-icon btn-light-info mb-2 view" title="Lihat" data-id="' . $a->uuid . '"><i class="fa fa-eye"></i></button>';
                    }
                }


            return $a;

                
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }
    public function jurnal_item($id) {
        if (request()->wantsJson( ) && request()->ajax()) {
        
        $MasterJurnal = MasterJurnal::where('masterjurnal_id', $id)->get();
            
            return response()->json([
                'status'    => true,
                'data'      => $MasterJurnal
            ], 200);
        } else {
            return abort(404);
        }
    }
    public function detail($uuid)
    {  if (request()->wantsJson( ) && request()->ajax()) {
        $data = MasterJurnal::with(["jurnal_item", "jurnal_item.account"])->where('uuid', $uuid)->first();

        $data->kredit = $data->jurnal_item->sum('kredit');
        $data->debit = $data->jurnal_item->sum('debit');
        return response()->json([
            'status'    => true,
            'data'      => $data
        ], 200);
    } else {
        return abort(404);
    }
    }
    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'kd_jurnal' => 'required|string',
                'tanggal'  => 'required|string',
                'type'  => 'required|string',
                'file'  => 'required|array',
                'file.*' => 'file',
                'jurnal_item'  => 'required|array',

            ]);
            $data = MasterJurnal::create($data);
            foreach ($request->file as $file) {
                $bukti['masterjurnal_id'] = $data->id;
                $bukti['file'] = 'storage/' . $file->store('bukti', 'public');
                BuktiMaster::create($bukti);
                # code...
            }

            
    
            for ($i = 0; $i < count($request->jurnal_item); $i++){
                $acc = Account::find($request->jurnal_item[$i]["account_id"]);
                $debit =  $request ->jurnal_item[$i]["debit"];
                $kredit =  $request->jurnal_item[$i]["kredit"];
    

                $data->jurnal_item()->create([
                        "masterjurnal_id" => $data->id,
                        "account_id" => $request->jurnal_item[$i]["account_id"],
                        "debit" => $debit,
                        "kredit" => $kredit,
                        "keterangan" => $request->jurnal_item[$i]["keterangan"],
                    ]);
            }
           

         
            



            return response()->json(['message' => ' Jurnal berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $MasterJurnal = MasterJurnal::with(['jurnal_item'])->get();
            
            return response()->json([
                'status'    => true,
                'data'      => $MasterJurnal
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid ) {
        if (request()->wantsJson() && request()->ajax()) {
            $MasterJurnal = MasterJurnal::with(['jurnal_item', 'BuktiMaster'])->where('uuid', $uuid)->first();
            $MasterJurnal->jurnal_item->map(function ($a) {
                $a->debit = number_format($a->debit, 2, ',', '.');
                $a->kredit = number_format($a->kredit, 2, ',', '.');
                return $a;
            });
            return response()->json($MasterJurnal);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'kd_jurnal' => 'required|string',
                'tanggal'  => 'required|string',
                'type'  => 'required|string',
                'file'  => 'required|array',
                'file.*' => 'file',
                'jurnal_item'  => 'required|array',


            ]);
            $master = MasterJurnal::where('uuid', $uuid)->first();
          
            $data = $request->only(['kd_jurnal','tanggal','type']);

            if ($master->update($data)) {
                JurnalItem::where('masterjurnal_id', $master->id)->delete();
                for ($i = 0; $i < count($request->jurnal_item); $i++) {
                    $debit =  $request->jurnal_item[$i]["debit"];
                    $kredit =  $request->jurnal_item[$i]["kredit"];
                    $item = JurnalItem::create([
                        "masterjurnal_id" => $master->id,
                        "account_id" => $request->jurnal_item[$i]["account_id"],
                        "debit" => $debit,
                        "kredit" =>  $kredit,
                        "keterangan" => $request->jurnal_item[$i]["keterangan"],
                    ]);
                }

                $buktis = BuktiMaster::where('masterjurnal_id', $master->id)->get();
                foreach ($buktis as $bukti) {
                    if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $bukti->file)))) {
                        unlink(storage_path('app/public/' . str_replace('storage/', '', $bukti->file)));
                    }
                    $bukti->delete();
                }
                if ($request->file('file')) {
                    foreach ($request->file('file') as $file) {
                        $bukti['masterjurnal_id'] = $master->id;
                        $bukti['file'] = 'storage/' . $file->store('bukti', 'public');
                        BuktiMaster::create($bukti);
                    }
                }
            }

     

            return response()->json(['message' => ' MasterJurnal berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $master = MasterJurnal::where('uuid', $uuid)->first();
            $buktis = BuktiMaster::where('masterjurnal_id', $master->id)->get();
            foreach ($buktis as $bukti) {
                $bukti->delete();
            }
            $master->delete();
            return response()->json(['message' => ' MasterJurnal berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
    public function getCode(Request $request)
    {

        $time = strtotime($request->tanggal);
        $day = date('d', $time);

        if ($request->type == 'umum') {
            $tanggal = 'JU';
        } else if ($request->type == 'penyesuaian') {
            $tanggal = 'JPS';
        } else {
            $tanggal = 'JPT';
        }

        $data = MasterJurnal::where("kd_jurnal",  "LIKE", "%" . $tanggal . "-" . $request->tahun . $request->bulan . $day . "%")->orderByDesc('id')->first();
        if (empty($data)) {
            return response()->json($tanggal . "-" . $request->tahun . $request->bulan . $day . "-1", 200);
        }
        $exp = explode("-", $data->kd_jurnal);
        return response()->json($tanggal . "-" . $request->tahun . $request->bulan . $day . "-" . strval($exp[2] + 1), 200);
    }
    public function checkTambah($tahun)
    {
        $data = MasterJurnal::whereYear('tanggal', $tahun)->first();

        return response()->json($data);
    }
   
}
