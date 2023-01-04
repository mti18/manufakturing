<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{

    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Pembayaran::where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('code', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'pembayaran' => 'required', // Sales Order atau Pembelian 
                'account_id' => 'nullable', 
                'accbiaya_id' => 'nullable', 
                'tanggal' => 'required', 
                'jenis_pembayaran' => 'required', 
                'bayar' => 'required', 
                'keterangan' => 'nullable',
            ]);
            Pembayaran::create($data);

            return response()->json(['message' => 'Pembayaran berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Pembayaran::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Pembayaran::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'account_id' => 'nullable', 
                'accbiaya_id' => 'nullable', 
                'tanggal' => 'required', 
                'jenis_pembayaran' => 'required', 
                'bayar' => 'required', 
                'keterangan' => 'nullable',
            ]);
            Pembayaran::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Pembayaran berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Pembayaran::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Pembayaran berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
