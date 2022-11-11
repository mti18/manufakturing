<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class CustomerController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Supplier::where('tipe', 'customer')->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('kode', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function ($a)
            {
                if (!empty($a->npwp)) {
                    $a->npwp = $a->npwp;
                } else {
                    $a->npwp = "-";
                }

                if (!empty($a->nppkp)) {
                    $a->nppkp = $a->nppkp;
                } else {
                    $a->nppkp = "-";
                }
            });

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
                'kode' => 'required|string',
                'npwp' => 'string|nullable', 
                'nppkp' => 'string|nullable', 
                'nama_kontak' => 'string|nullable', 
                'telp_kontak' => 'string|nullable', 
                'alamat' => 'required|string',
                'provinsi_id' => 'required|integer',
                'kab_kota_id' => 'required|integer',
                'kecamatan_id' => 'required|integer',
            ]);

            $request->merge([
                'tipe' => 'customer'
            ]);

            Supplier::create($request->all());

            return response()->json(['message' => 'Data customer berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Supplier::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Supplier::where('uuid', $uuid)->first();
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
                'kode' => 'required|string',
                'npwp' => 'string|nullable', 
                'nppkp' => 'string|nullable', 
                'nama_kontak' => 'string|nullable', 
                'telp_kontak' => 'string|nullable', 
                'alamat' => 'required|string',
                'provinsi_id' => 'required|integer',
                'kab_kota_id' => 'required|integer',
                'kecamatan_id' => 'required|integer',
            ]);
            Supplier::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Data customer berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Supplier::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Data customer berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
