<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrderDetail;

class SalesOrderDetailController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = SalesOrderDetail::where(function ($q) use ($request) {
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
                'volume' => 'required|numeric',
                'nm_satuan' => 'required',
                'harga' => 'required|numeric',
                'diskon' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'keterangan' => 'nullable',
            ]);
            SalesOrderDetail::create($data);

            return response()->json(['message' => 'Detail berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = SalesOrderDetail::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = SalesOrderDetail::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'volume' => 'required|numeric',
                'nm_satuan' => 'required',
                'harga' => 'required|numeric',
                'diskon' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'keterangan' => 'nullable',
            ]);
            SalesOrderDetail::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'DetBarangil berhasil diedit']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            SalesOrderDetail::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Barang berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
