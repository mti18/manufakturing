<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class PembelianController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Pembelian::with('supplier', 'diketahui_oleh', 'profile')->where(function ($q) use ($request) {
                $q->where('supplier_id', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('account_id', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

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
                'nomor' => 'nullable|string',
                'no_surat' => 'required|string', 
                'tgl_permintaan' => 'required|string', 
                'bukti_permintaan' => 'required|string', 
                'no_surat_pembelian' => 'required|string', 
                'diketahui_oleh' => 'nullable|string',
                'tgl_po' => 'required|string',
                'jenis_pembayaran' => 'required',
                'no_po_pembelian' => 'required|string',
                'account_id' => 'nullable|integer',
                'no_surat_jalan' => 'required|string',
                'tempo' => 'required|numeric',
                'keterangan' => 'string|nullable',
                'jml_penjualan' => 'nullable||numeric',
                'diskon'  => 'nullable||numeric', 
                'uangmuka' => 'nullable||numeric', 
                'pajak'  => 'nullable||numeric', 
                'ppn'  => 'nullable||numeric', 
                'netto' => 'nullable||numeric',
            ]);
            $data['tipe'] = '1'; // (1) Pembelian // (2) Pembelian Internal
            $data = Pembelian::create($request->all());
            $data = Pembelian::where('id', $data->id)->first();
            // $data = Pembelian::with(['details'])->where('id', $data->id)->first();

            return response()->json(['message' => 'Data pembelian berhasil diperbarui', 'data' => $data]);
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
                'nomor' => 'nullable|string',
                'no_surat' => 'required|string', 
                'tgl_permintaan' => 'required|string', 
                'bukti_permintaan' => 'required|string', 
                'no_surat_pembelian' => 'required|string', 
                'diketahui_oleh' => 'nullable|string',
                'tgl_po' => 'required|string',
                'jenis_pembayaran' => 'required',
                'no_po_pembelian' => 'required|string',
                'account_id' => 'nullable|integer',
                'no_surat_jalan' => 'required|string',
                'tempo' => 'required|numeric',
                'keterangan' => 'string|nullable',
                'jml_penjualan' => 'nullable|numeric',
                'diskon'  => 'nullable|numeric', 
                'uangmuka' => 'nullable|numeric', 
                'pajak'  => 'nullable|numeric', 
                'ppn'  => 'nullable|numeric', 
                'netto' => 'nullable|numeric',
            ]);

            $jml_penjualan = $data['jml_penjualan'];
            $diskon = $data['diskon'];
            $uangmuka = $data['uangmuka'];
            $netto = $data['netto'];

            $jml_penjualan = str_replace('.', '', $jml_penjualan);
            $jml_penjualan = (double)str_replace(',', '.', $jml_penjualan);
            $data['jml_penjualan'] = $jml_penjualan;
            $diskon = str_replace('.', '', $diskon);
            $diskon = (double)str_replace(',', '.', $diskon);
            $data['diskon'] = $diskon;
            $uangmuka = str_replace('.', '', $uangmuka);
            $uangmuka = (double)str_replace(',', '.', $uangmuka);
            $data['uangmuka'] = $uangmuka;
            $netto = str_replace('.', '', $netto);
            $netto = (double)str_replace(',', '.', $netto);
            $data['netto'] = $netto;

            $data = Pembelian::where('uuid', $uuid)->update($data);
            
            // $data = Pembelian::with(['details'])->where('id', $data->id)->first();

            return response()->json(['message' => 'Data pembelian berhasil diperbarui', 'data' => $data]);
        } else {
            return abort(404);
        }
    }

    public function gettahun() {
        if (request()->wantsJson()) {
            $data = Pembelian::all();
            $tahun = Carbon::now()->format('Y');
            return $tahun;
        } else {
            return abort(404);
        }
    }

    public function getbulan() {
        if (request()->wantsJson()) {
            $bulan= Carbon::now()->format('m');
            $bulanromawi = AppHelper::BulanToRomawi($bulan);
            return $bulanromawi;
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

    
    public function getnomor()
    {
        $data = Pembelian::pluck('no_surat')->toArray();
        $a = [];


        
        foreach($data as $item){
            $exp = explode("/", $item);
            $a[] = $exp[0];
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

    public function getnomorbyid($id)
    {
        $data = Pembelian::findByUuid($id)->nomor;
        $exp = explode("-",$data);
        return $exp[1];
    }
}
