<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Supplier::where('tipe', 'supplier')->where(function ($q) use ($request) {
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
                'kode' => 'required|string',
                'telepon' => 'required|numeric', 
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
                'tipe' => 'supplier',
                
                // 'kode' => $this->getCode($request->parent_id)
            ]);
            
            Supplier::create($request->all());

            return response()->json(['message' => 'Data supplier berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Supplier::where('tipe', 'supplier')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function show() {
        if (request()->wantsJson()) {
            $data = Supplier::get();
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

            return response()->json(['message' => 'Data supplier berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }


    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Supplier::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Data supplier berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getcodebyid($id)
    {
        $data = Supplier::findByUuid($id)->kode;
        $exp = explode("-",$data);
        return $exp[1];
    }

    public function getcode()
    {
        $data = Supplier::where('tipe', 'supplier')->pluck('kode')->toArray();
        $a = [];

        foreach($data as $item){
              $exp = explode("-", $item);
              $a[] = $exp[1];
        }

        
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
