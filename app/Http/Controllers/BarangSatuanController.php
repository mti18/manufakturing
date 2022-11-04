<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SatuanChild;
use App\Models\BarangSatuan;

class BarangSatuanController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangSatuan::where(function ($q) use ($request) {
                $q->where('nm_satuan', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function child($id)
    {
        $data = SatuanChild::where('barangsatuan_id', $id)->get();
        return RestApi::success($data);
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_satuan' => 'required|string',
                'satuan' => 'required|string',
            ]);
            
            $data = BarangSatuan::create($request->only(['nm_satuan', 'satuan']));

            $parent_id = null;

            foreach($request->child as $item){
                $item['parent_id'] = $parent_id;
                $item['barangsatuan_id'] = $data->id;

                $child = SatuanChild::create($item);
                $parent_id = $child->id;
            }


            return response()->json(['message' => 'Satuan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = BarangSatuan::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = BarangSatuan::with('child')->where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_satuan' => 'required|string',
                'satuan' => 'required|string',
            ]);
            $data = BarangSatuan::findByUuid($uuid);


            SatuanChild::where('barangsatuan_id', $data->id)->delete();
            
            $parent_id = null;

            foreach($request->child as $item){
                $item['parent_id'] = $parent_id;
                $item['barangsatuan_id'] = $data->id;
                
                $child = SatuanChild::create($item);
                $parent_id = $child->id;
            }
            $data->update($request->all());



            

            return response()->json(['message' => 'Satuan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            BarangSatuan::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Satuan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}


