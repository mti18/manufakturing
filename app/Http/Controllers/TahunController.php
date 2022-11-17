<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Tahun::where(function ($q) use ($request) {
                $q->where('nm_Tahun', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function ($a) {
                $a->potongan .= ' %';
                $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="' . $a->uuid . '"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Delete" data-id="' . $a->uuid . '"><i class="fa fa-trash"></i></button>';
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
                'tahun' => 'required|string',

            ]);
           
            $data = Tahun::create($request->only(['tahun']));



            return response()->json(['message' => ' Tahun berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $tahun = Tahun::get();
            
            return response()->json([
                'status'    => true,
                'data'      => $tahun
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $tahun = Tahun::findByUuid($uuid);
            return response()->json($tahun);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tahun' => 'required|string',

            ]);
            $data = Tahun::findByUuid($uuid);
            
         
            $data->update($request->all());
     

            return response()->json(['message' => ' Tahun berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Tahun::where('uuid', $uuid)->delete();
            return response()->json(['message' => ' Tahun berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
