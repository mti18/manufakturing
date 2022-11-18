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
                'logo' => 'required|image',
                'kop' => 'nullable|image',
                'ttd' => 'nullable|image',
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

            
            $data['logo'] = 'storage/' . $request->logo->store('profile', 'public');
            $data['kop'] = 'storage/' . $request->kop->store('profile', 'public');
            $data['ttd'] = 'storage/' . $request->ttd->store('profile', 'public');

            $profile = Profile::create($data);

            return response()->json([
                'message' => 'Profile baru berhasil ditambahkan',
            ]);
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
            $profile = Profile::where('uuid', $uuid)->first();

            $data = $request->validate([
                'logo' => 'required|image',
                'kop' => 'required|image',
                'ttd' => 'required|image',
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

            $profile = Profile::where('uuid', $uuid)->first();
                if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $profile->logo)))) {
                    unlink(storage_path('app/public/' . str_replace('storage/', '', $profile->logo)));
                }
                if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $profile->kop)))) {
                    unlink(storage_path('app/public/' . str_replace('storage/', '', $profile->kop)));
                }
                if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $profile->ttd)))) {
                    unlink(storage_path('app/public/' . str_replace('storage/', '', $profile->ttd)));
                }

            $data['logo'] = 'storage/' . $request->logo->store('profile', 'public');
            $data['kop'] = 'storage/' . $request->kop->store('profile', 'public');
            $data['ttd'] = 'storage/' . $request->ttd->store('profile', 'public');


            $profile->update($data);

            return response()->json([
                'message' => $profile->nama . ' berhasil diperbarui',
            ]);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $profile = Profile::where('uuid', $uuid)->first();
            $profile->delete();
            return response()->json(['message' => 'Profile berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
