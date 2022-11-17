<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GolonganController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Golongan::where(function ($q) use ($request) {
            $q->where('nm_golongan', 'LIKE', '%'.$request->search.'%')
                ->orWhere('metode_penyusutan', 'LIKE', '%'.$request->search.'%')
                ->orWhere('masa', 'LIKE', '%'.$request->search.'%')
                ->orWhere('rate', 'LIKE', '%'.$request->search.'%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a) {
                $a->masa = $a->masa.' Tahun';
                $a->rate = $a->rate.' %';

                $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="'.$a->uuid.'"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Hapus" data-id="'.$a->uuid.'"><i class="fa fa-trash"></i></button>';
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
                'nm_golongan' => 'required|string',
                'metode_penyusutan' => 'required|string',
                'masa' => 'required|numeric',
                'rate' => 'required|numeric',

            ]);
           
            $data = Golongan::create($data);



            return response()->json(['message' => ' Golongan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $Golongan = Golongan::get();
            
            return response()->json([
                'status'    => true,
                'data'      => $Golongan
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $Golongan = Golongan::findByUuid($uuid);
            return response()->json($Golongan);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_golongan' => 'required|string',
                'metode_penyusutan' => 'required|string',
                'masa' => 'required|string',
                'rate' => 'required|numeric',

            ]);
            $data = Golongan::findByUuid($uuid);
            
         
            $data->update($request->all());
     

            return response()->json(['message' => ' Golongan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Golongan::where('uuid', $uuid)->delete();
            return response()->json(['message' => ' Golongan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
