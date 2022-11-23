<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\MasterJurnal;
// use App\Models\Bulan;
// use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJurnalController extends Controller
{
    public function paginate(Request $request, $bulan, $tahun) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = MasterJurnal::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where(function ($q) use ($request) {
                $q->where('tanggal', 'LIKE', '%' . $request->search . '%');
            })->orderBy('tanggal', 'asc')->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);

            // Add Columns
            $courses->map(function ($a) {
                $a->tanggal = AppHelper::tanggal_indo($a->tanggal);

            return $a;

                
            });

            return response()->json($courses);
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
                'upload'  => 'required|image',

            ]);
            $data['upload'] = 'storage/' . $request->upload->store('bukti', 'public');
            $data = MasterJurnal::create($data);



            return response()->json(['message' => ' MasterJurnal berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $MasterJurnal = MasterJurnal::get();
            
            return response()->json([
                'status'    => true,
                'data'      => $MasterJurnal
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $MasterJurnal = MasterJurnal::findByUuid($uuid);
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
                'upload'  => 'required|image',


            ]);
            
            $profile = MasterJurnal::where('uuid', $uuid)->first();
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $profile->upload)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $profile->upload)));
            }
          
            $data['upload'] = 'storage/' . $request->upload->store('bukti', 'public');
            
         
            $data->update($request->all());
     

            return response()->json(['message' => ' MasterJurnal berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            MasterJurnal::where('uuid', $uuid)->delete();
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
    // public function send(Request $request) {
    //     if (request()->wantsJson() && request()->ajax()) {
    //         $data = $request->validate([
    //             'tahun' => 'required|string',
    //             'bulan' => 'required|string'
                
    //         ]);
           
    //         $data = Bulan::create($data);
    //         $data = Tahun::create($data);



    //         return response()->json(['message' => ' MasterJurnal berhasil ditambahkan']);
    //     } else {
    //         return abort(404);
    //     }
    // }

}
