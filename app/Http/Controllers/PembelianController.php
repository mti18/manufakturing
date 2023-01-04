<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use App\Models\PembelianDetail;
use App\Models\SatuanJadiChild;
use App\Models\User;
use PDF;

class PembelianController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Pembelian::with('supplier', 'supplier.provinsi', 'supplier.kota', 'supplier.kecamatan', 'profile', 
            'profile.provinsi', 'profile.kecamatan', 'profile.kelurahan', 'profile.kota', 'diketahuioleh', 'details',
             'barangjadi', 'barangmentah', 'permintaan', 'barang_jadi', 'barang_mentah','user')->where(function ($q) use ($request) {
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
                'jml_penjualan' => 'nullable|numeric',
                'diskon'  => 'nullable|numeric', 
                'uangmuka' => 'nullable|numeric', 
                'pajak'  => 'nullable|numeric', 
                'ppn'  => 'nullable|numeric', 
                'netto' => 'nullable|numeric',
            ]);
            
            $request->merge([
                'tipe' => '1' // (1) Pembelian // (2) Pembelian Internal
            ]);
            $data['user_id'] = auth()->user()->id;

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
            $data = Pembelian::with(['barangjadi', 'barangmentah'])->where('uuid', $uuid)->first();
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

            $data['tipe'] = '1';

            $jml_penjualan = $data['jml_penjualan'];
            $diskon = $data['diskon'];
            $uangmuka = $data['uangmuka'];
            $pajak = $data['pajak'];
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
            $pajak = str_replace('.', '', $pajak);
            $pajak = (double)str_replace(',', '.', $pajak);
            $data['pajak'] = $pajak;
            $netto = str_replace('.', '', $netto);
            $netto = (double)str_replace(',', '.', $netto);
            $data['netto'] = $netto;

            $pembelian = Pembelian::where('uuid', $uuid)->first();
            $data = $request->only(['profile_id', 'supplier_id', 'nomor', 'no_surat', 'tgl_permintaan', 'bukti_permintaan',
                'no_surat_pembelian', 'diketahui_oleh', 'tgl_po', 'jenis_pembayaran', 'no_po_pembelian', 'account_id',
                'no_surat_jalan', 'tempo', 'keterangan', 'jml_penjualan', 'diskon', 'uangmuka', 'pajak', 'ppn', 'netto', 'tipe'
            ]);
            // $data = Pembelian::with(['details'])->where('id', $data->id)->first();
            if ($pembelian->update($data)) {
                PembelianDetail::where('pembelian_id', $pembelian->id)->delete();

                foreach ($request->detail['barangmentah'] as $item) {
                    $harga = $item['harga'];
                    $jumlah = $item['jumlah'];

                    $harga = str_replace('.', '', $harga);
                    $harga = (double)str_replace(',', '.', $harga);
                    $item['harga'] = $harga;
                    $jumlah = str_replace('.', '', $jumlah);
                    $jumlah = (double)str_replace(',', '.', $jumlah);
                    $item['jumlah'] = $jumlah;

                    // $child = SatuanChild::find($data['satuan']);
                    // if ($data['volume'] > ) {
                    //     $volume = $data['volume'];
                    //     $volume = $volume * $child->nilai;
                    //     $data['volume'] = $volume;
                    // }
    
                        PembelianDetail::create([
                            'permintaan_id' => $item['permintaan_id'],
                            'harga' => $item['harga'],
                            'jumlah' => $item['jumlah'],
                            'pembelian_id' => $pembelian->id,
    
                        ]);
                }

                foreach ($request->detail['barangjadi'] as $item) {
                    $harga = $item['harga'];
                    $jumlah = $item['jumlah'];
                    
                    $harga = str_replace('.', '', $harga);
                    $harga = (double)str_replace(',', '.', $harga);
                    $item['harga'] = $harga;
                    $jumlah = str_replace('.', '', $jumlah);
                    $jumlah = (double)str_replace(',', '.', $jumlah);
                    $item['jumlah'] = $jumlah;

                    $child = SatuanJadiChild::find($item['satuan']);
                    $volume = $item['volume'] * $child->nilai;
                    $item['volume'] = $volume;

                        PembelianDetail::create([
                            'permintaan_id' => $item['permintaan_id'],
                            'harga' => $item['harga'],
                            'jumlah' => $item['jumlah'],
                            'pembelian_id' => $pembelian->id,
    
                        ]);
                }

            return response()->json(['message' => 'Data pembelian berhasil diperbarui', 'data' => $data]);
        } else {
            return abort(404);
        }
    }
}
    
    public function gettahun() {
        if (request()->wantsJson()) {
            $tahun = Carbon::now()->format('Y');
            return $tahun;
        } else {
            return abort(404);
        }
    }

    public function getbulan() {
        if (request()->wantsJson()) {
            $bulan= Carbon::now()->format('n');
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
        $data = Pembelian::findByUuid($id)->no_surat;
        $exp = explode("-",$data);
        return $exp[1];
    }



    public function generatepdf($uuid)
    {
        $user = User::find(auth()->user()->id);
        $data = Pembelian::with(['supplier', 'supplier.provinsi', 'supplier.kota', 'supplier.kecamatan', 'profile', 
            'profile.provinsi', 'profile.kecamatan', 'profile.kelurahan', 'profile.kota', 'diketahuioleh', 'details',
             'barangjadi', 'barangmentah', 'permintaan', 'barang_jadi', 'barang_mentah', 'user'
        ])->where('uuid', $uuid)->first();
        $no_surat = $data['no_surat'];
        $pdf = PDF::loadview('laporan.pembelian.Index', ['data' => $data, 'user' => $user]);
        return $pdf->download('Pembelian - ' . $no_surat);
    }
}
