<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermintaanBarang;

class PermintaanInternalController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = PermintaanBarang::where(function ($q) use ($request) {
                $q->where('volume', 'LIKE', '%' . $request->search . '%');
                // $q->orWhere('nm_provinsi', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);



            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tanggal_permintaan'  => 'nullable', 
                'no_bukti_permintaan'  => 'nullable', 
                'volume'  => 'required||numeric', 
                'harga'  => 'required||numeric', 
                'jumlah'  => 'required||numeric', 
                'keterangan'  => 'string||nullable'
            ]);
            $data['tipe'] = 'internal';
            PermintaanBarang::create($data);

            return response()->json(['message' => 'Permintaan Internal berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = PermintaanBarang::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = PermintaanBarang::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tanggal_permintaan'  => 'nullable', 
                'no_bukti_permintaan'  => 'nullable', 
                'volume'  => 'required||numeric', 
                'harga'  => 'required||numeric', 
                'jumlah'  => 'required||numeric', 
                'keterangan'  => 'string||ullable'
            ]);
            $data['tipe'] = 'internal';
            PermintaanBarang::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Permintaan Internal berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            PermintaanBarang::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Permintaan Internal berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
