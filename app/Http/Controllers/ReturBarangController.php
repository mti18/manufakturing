<?php

namespace App\Http\Controllers;

use App\Models\ReturBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturBarangController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = ReturBarang::where(function ($q) use ($request) {
                // $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
            
        } else {
            return abort(404);
        }
    }
    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_kategori' => 'required|string',
                'barangjadi_id' => 'nullable',
                'barangmentah_id' => 'nullable',
                'jumlah' => 'required|numeric',
                'keterangan' => 'nullable',
            ]);

            $user = User::find(auth()->user()->id);
            $data['user_id'] = $user;

            ReturBarang::create($data);

            return response()->json(['message' => 'Retur Barang berhasil ditambah']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = ReturBarang::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = ReturBarang::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tanggal' => 'required|string',
	            'salesorder_detail_id' => 'required|integer',
                'jumlah' => 'required|numeric',
                'keterangan' => 'nullable',
                ]);

                $user = User::find(auth()->user()->id);
                $data['user_id'] = $user;

            ReturBarang::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Retur Barang berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            ReturBarang::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Retur Barang berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
