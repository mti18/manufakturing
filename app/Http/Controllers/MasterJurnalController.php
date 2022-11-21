<?php

namespace App\Http\Controllers;

use App\Models\MasterJurnal;
// use App\Models\Bulan;
// use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJurnalController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = MasterJurnal::where(function ($q) use ($request) {
               $q->where('kd_jurnal', 'LIKE', '%'.$request->search.'%')
                ->orWhere('tanggal', 'LIKE', '%'.$request->search.'%');
               
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

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

            ]);
           
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

            ]);
            $data = MasterJurnal::findByUuid($uuid);
            
         
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
