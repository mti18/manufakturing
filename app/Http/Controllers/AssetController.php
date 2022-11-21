<?php

namespace App\Http\Controllers;


use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AssetController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Asset::where(function ($q) use ($request) {

                $q->where('nama', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a){
            	$a->action = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm tambah" title="Tambah" data-id="'.$a->id.'" data-nama="'.$a->nama.'"><i class="la la-plus kt-font-success"></i></button>';

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
                'nm_assets' => 'required|string', 
                'tahun' => 'required|numeric', 
                'kelompok_id' => 'required', 
                'jumlah' => 'required', 
                'profile_id' => 'required', 
                'jenisasset_id' => 'required',
            ]);
            
            Asset::create($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Asset::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Asset::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_assets' => 'required|string', 
                'tahun' => 'required|numeric', 
                'kelompok_id' => 'required', 
                'jumlah' => 'required', 
                'profile_id' => 'required', 
                'jenisasset_id' => 'required',
            ]);

            // $data['kelompok'] = Kelompok::where('id', $request->kelompok_id)->first()->id;
            // $data['profile'] = Profile::where('id', $request->profile_id)->first()->id;
            // $data['jenisasset'] = JenisAsset::where('id', $request->jenisasset_id)->first()->id;


            // return 'oioi';
            Asset::create($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Asset::where('uuid', $uuid)->delete();
         return response()->json(['message' => 'Jabatan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
