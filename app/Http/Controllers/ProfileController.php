<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Profile::where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nama' => 'required|string',
                'telepon' => 'required|numeric', 
                'pimpinan' => 'required|string',
                'fax' => 'string', 
                'npwp' => 'string', 
                'alamat' => 'required|string',
                'provinsi_id' => 'required|integer',
                'kab_kota_id' => 'required|integer',
                'kecamatan_id' => 'required|integer',
                'kelurahan_id' => 'required|integer',
            ]);

            Profile::create($request->all());

            return response()->json(['message' => 'Data customer berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Profile::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Profile::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nama' => 'required|string',
                'telepon' => 'required|numeric', 
                'pimpinan' => 'required|string',
                'fax' => 'string', 
                'npwp' => 'string', 
                'alamat' => 'required|string',
                'provinsi_id' => 'required|integer',
                'kab_kota_id' => 'required|integer',
                'kecamatan_id' => 'required|integer',
                'kelurahan_id' => 'required|integer',
            ]);
            Profile::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Data customer berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Profile::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Data customer berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
