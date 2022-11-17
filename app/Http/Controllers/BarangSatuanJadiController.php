<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangSatuanJadi;
use App\Models\SatuanJadiChild;

class BarangSatuanJadiController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangSatuanJadi::where(function ($q) use ($request) {
                $q->where('nm_satuan_jadi', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function ($a)
            {
                $a->nm_satuan_jadi = "Satuan " . $a->nm_satuan_jadi;
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function child($id)
    {
        $data = SatuanJadiChild::where('barangsatuanjadi_id', $id)->get();
        return RestApi::success($data);
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_satuan_jadi' => 'required|string',
                'child' => 'required|array',
            ]);
           $data = BarangSatuanJadi::create($data);
            foreach($request->child as $item){

                $nilai = $item['nilai'];
                $nm_satuan_jadi_children = $item['nm_satuan_jadi_children'];
                    SatuanJadiChild::create([
                        'nm_satuan_jadi_children' => $nm_satuan_jadi_children,
                        'nilai' => $nilai,
                        'barangsatuanjadi_id' => $data->id,
                    ]);
            }

            return response()->json(['message' => 'Satuan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = BarangSatuanJadi::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = BarangSatuanJadi::with('child')->where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_satuan_jadi' => 'required|string',
                'child' => 'required|array',

            ]);

            $barangsj = BarangSatuanJadi::where('uuid', $uuid)->first();
            
            if ($barangsj->update($data)) {
                foreach($request->child as $item){
    
                    $nilai = $item['nilai'];
                    $nm_satuan_jadi_children = $item['nm_satuan_jadi_children'];

                    SatuanJadiChild::where('barangsatuanjadi_id', $barangsj->id)->delete();

                        SatuanJadiChild::create([
                            'nm_satuan_jadi_children' => $nm_satuan_jadi_children,
                            'nilai' => $nilai,
                            'barangsatuanjadi_id' => $barangsj->id,
                        ]);
            }
            }

            return response()->json(['message' => 'Satuan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            BarangSatuanJadi::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Satuan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
