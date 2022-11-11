<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelurahan;

class KelurahanController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Kelurahan::where(function ($q) use ($request) {
                $q->where('nm_kelurahan', 'LIKE', '%' . $request->search . '%');
                // $q->orWhere('code', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a) {
                $a->namakec =$a->kecamatan->nm_kecamatan;
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
                'nm_kelurahan' => 'required',
                'kecamatan_id' => 'required',
            ]);
            Kelurahan::create($data);

            return response()->json(['message' => 'Kelurahan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Kelurahan::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Kelurahan::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_kelurahan' => 'required',
                'kecamatan_id' => 'required',
            ]);
            Kelurahan::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Kelurahan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Kelurahan::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Kelurahan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
