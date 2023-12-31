<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BulanController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Bulan::where(function ($q) use ($request) {
                $q->where('nm_Bulan', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

             $courses->map(function ($a) {
                $a->potongan .= ' %';
                $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="' . $a->uuid . '"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Delete" data-id="' . $a->uuid . '"><i class="fa fa-trash"></i></button>';
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
                'bulan' => 'required|string',

            ]);
           
            $data = Bulan::create($data->only(['bulan']));



            return response()->json(['message' => ' Bulan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $bulan = Bulan::get();
            
            return response()->json([
                'status'    => true,
                'data'      => $bulan
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $bulan = Bulan::findByUuid($uuid);
            return response()->json($bulan);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'bulan' => 'required|string',

            ]);
            $data = Bulan::findByUuid($uuid);
            
         
            $data->update($request->all());
     

            return response()->json(['message' => ' Bulan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Bulan::where('uuid', $uuid)->delete();
            return response()->json(['message' => ' Bulan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
