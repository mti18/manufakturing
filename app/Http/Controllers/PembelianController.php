<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;


class PembelianController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Pembelian::with('supplier', 'diketahui_oleh')->where(function ($q) use ($request) {
                $q->where('supplier_id', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('account_id', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            // $courses->map(function ($a)
            // {
            //     if (!empty($a->npwp)) {
            //         $a->npwp = $a->npwp;
            //     } else {
            //         $a->npwp = "-";
            //     }

            //     if (!empty($a->nppkp)) {
            //         $a->nppkp = $a->nppkp;
            //     } else {
            //         $a->nppkp = "-";
            //     }
            // });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'profile_id' => 'required|numeric',
                'supplier_id' => 'required|numeric', 
                'no_surat' => 'required|string', 
                'tgl_permintaan' => 'required|string', 
                'bukti_permintaan' => 'required|string', 
                'no_surat_pembelian' => 'required|string', 
                'diketahui_oleh' => 'nullable|string',
                'tgl_po' => 'required|string',
                'jenis_pembayaran' => 'required',
                'no_po_pembelian' => 'required|string',
                'account_id' => 'required|integer',
                'no_surat_jalan' => 'required|string',
                'tempo' => 'required|numeric',
                'keterangan' => 'string|nullable',
            ]);
            Pembelian::create($request->all());

            return response()->json(['message' => 'Data supplier berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Pembelian::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Pembelian::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'profile_id' => 'required|numeric',
                'supplier_id' => 'required|numeric', 
                'no_surat' => 'required|string', 
                'tgl_permintaan' => 'required|string', 
                'bukti_permintaan' => 'required|string', 
                'no_surat_pembelian' => 'required|string', 
                'diketahui_oleh' => 'nullable|string',
                'tgl_po' => 'required|string',
                'jenis_pembayaran' => 'required',
                'no_po_pembelian' => 'required|integer',
                'account_id' => 'required|integer',
                'no_surat_jalan' => 'required|integer',
                'tempo' => 'required|numeric',
                'keterangan' => 'string|nullable',
            ]);
            $data = Pembelian::where('uuid', $uuid)->update($data);
            $data = Pembelian::with(['details'])->where('id', $data->id)->first();

            return response()->json(['message' => 'Data pembelian berhasil diperbarui', 'data' => $data]);
        } else {
            return abort(404);
        }
    }


    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Pembelian::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Data pembelian berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getcodebyid($id)
    {
        $data = Pembelian::findByUuid($id)->kode;
        $exp = explode("-",$data);
        return $exp[1];
    }

    public function getnomor()
    {
        $data = Pembelian::pluck('nomor')->toArray();
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