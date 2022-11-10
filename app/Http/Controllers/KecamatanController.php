<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kecamatan;


class KecamatanController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Kecamatan::with('kota')->where(function ($q) use ($request) {
                $q->where('nm_kecamatan', 'LIKE', '%' . $request->search . '%');
                // $q->orWhere('code', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a) {
                $a->kabkota =$a->kota->nm_kab_kota;
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
                'nm_kecamatan' => 'required',
                'kab_kota_id' => 'required',
            ]);
            Kecamatan::create($data);

            return response()->json(['message' => 'Kecamatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Kecamatan::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Kecamatan::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_kecamatan' => 'required',
                'kab_kota_id' => 'required',
            ]);
            Kecamatan::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Kecamatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Kecamatan::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Kecamatan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
