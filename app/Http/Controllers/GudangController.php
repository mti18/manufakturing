<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\Gudang;
use App\Models\Rak;
use App\Models\SatuanJadiChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Gudang::with(['rak'])->where(function ($q) use ($request) {
                $q->where('nm_gudang', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('kode', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function ($a)
            {
                // if (!empty($a->rak)) {
                    // $a->rak = $a->rak;
            //     }else {
            //         $a->rak = "Belum ada Rak";
            //     }
            });


            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function rak($id)
    {
        $data = Rak::where('gudang_id', $id)->get();
        return RestApi::success($data);
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_gudang' => 'required|string',
                'kode' => 'required|string',
                'rak' => 'required|array',
            ]);

            $data = Gudang::create($data);

            foreach($request->rak as $item){
                $nm_rak = $item['nm_rak'];

                    Rak::create([
                        'nm_rak' => $nm_rak,
                        'gudang_id' => $data->id,
                    ]);
            }

            return response()->json(['message' => 'Gudang berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Gudang::with(['rak'])->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Gudang::with(['rak'])->where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_gudang' => 'required|string',
                'rak' => 'required|array',
            ]);

            $gudang = Gudang::where('uuid', $uuid)->first();
            $data = $request->only(['nm_gudang']);
            
            if ($gudang->update($data)) {
                Rak::where('gudang_id', $gudang->id)->delete();
                foreach($request->rak as $item){
                    $nm_rak = $item['nm_rak'];
    
                        Rak::create([
                            'nm_rak' => $nm_rak,
                            'gudang_id' => $gudang->id,
                        ]);
                }
            }

            

            return response()->json(['message' => 'Gudang berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Gudang::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Gudang berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getcode()
    {
        
        $a = Gudang::pluck('kode')->toArray();
        
        if(count($a) > 0){
            sort($a);
            $start = 1;
            for ($i=0; $i < count($a); $i++) { 
                if((int)$a[$i] != $start){
                    return str_pad($start,4,"0",STR_PAD_LEFT);
                }
                $start++;
            }
            return str_pad($start,4,"0",STR_PAD_LEFT);
            
        }
        return str_pad('1',4,"0",STR_PAD_LEFT);
    }

}
