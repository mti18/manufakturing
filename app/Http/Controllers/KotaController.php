<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kota;

class KotaController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Kota::where(function ($q) use ($request) {
                $q->where('nm_kab_kota', 'LIKE', '%' . $request->search . '%');
                // $q->orWhere('nm_provinsi', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a) {
                $a->namaprov =$a->provinsi->nm_provinsi;
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
                'nm_kab_kota' => 'required',
                'provinsi_id' => 'required',
            ]);
            Kota::create($data);

            return response()->json(['message' => 'Kota berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Kota::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Kota::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_kab_kota' => 'required',
                'provinsi_id' => 'required',
            ]);
            Kota::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Kota berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Kota::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Kota berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
