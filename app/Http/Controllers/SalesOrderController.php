<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\SatuanChild;
use App\Models\SatuanJadiChild;
use App\Models\User;
use PDF;


class SalesOrderController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = SalesOrder::with(['supplier', 'profile', 'diketahuioleh', 'detail', 'user'])->where(function ($q) use ($request) {
                $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('profile_id', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('supplier_id', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);


            // $courses->map(function ($a)
            // {
            //     if($a->status=='draft'){
            //         $a->status='<span class="label label-danger label-pill label-inline mr-2">Draft</span>';
            //     }
            //     elseif ($a->status=='process') {
            //         $a->status='<span class="label label-danger label-pill label-inline mr-2">Process</span>';
            //     }
            //     elseif ($a->staus=='ready') {
            //         $a->status='<span class="label label-danger label-pill label-inline mr-2">Ready</span>';
            //     }
            //     else {
            //         $a->status='<span class="label label-danger label-pill label-inline mr-2">Success</span>';
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
                'profile_id' => 'required', 
                'supplier_id' => 'required', 
                'diketahui_oleh' => 'nullable', 
                'jumlah_paket' => 'nullable|numeric', 
                'bukti_pesan' => 'nullable|string', 
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer,Free', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'no_pemesanan' => 'required',
                'detail' => 'required|array',
                'tempo' => 'nullable|numeric',
                'keterangan' => 'nullable', 
                'total' => 'nullable|numeric', 
                'diskon' => 'nullable|numeric', 
                'uangmuka' => 'nullable|numeric', 
                'pph' => 'nullable', 
                'ppn' => 'nullable', 
                'netto' => 'nullable|numeric',
            ]);

            // $data['status'] = '1';
            // $data['pembayaran'] = ($request->tempo == '0') ? 'yes' : 'no';

            // $user = User::find(auth()->user()->id);
            $data['user_id'] = auth()->user()->id;

            $data = SalesOrder::create($data);

            $data = SalesOrder::where('id', $data->id)->first();

            

            return response()->json(['message' => 'Sales Order berhasil diperbarui', 'data' => $data]);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = SalesOrder::with(['barangjadi', 'barangmentah'])->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = SalesOrder::with(['supplier', 'profile', 'diketahuioleh', 'detail'])->where('uuid', $uuid)->first();
            $diskon = $data['diskon'];
            if ($diskon <= 100) {
                $data['tipe_diskon'] = "persen";
            } else {
                $data['tipe_diskon'] = "rupiah";
            }

            // if ($data['barangjadi_id'] != null) {
            //     $data->child = $data->barang_jadi->barangsatuanjadi->child;
    
            //     $satuanjadi = SatuanJadiChild::where('barangsatuanjadi_id', $data->barang_jadi->barangsatuanjadi_id)->orderBy('nilai')->first()->id;
            //     $data->satuan = $satuanjadi;
            // } else {
            //     $data->child = $data->barang_mentah->barangsatuan->child;
    
            //     $satuanmentah = SatuanChild::where('barangsatuan_id', $data->barang_mentah->barangsatuan_id)->id;
            //     $data->satuan = $satuanmentah;
            // }
            

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
                'diketahui_oleh' => 'nullable|numeric', 
                'jumlah_paket' => 'nullable|numeric', 
                'bukti_pesan' => 'required|string', 
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer,Free', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'tempo' => 'nullable|numeric',
                'keterangan' => 'required', 
                'total' => 'required|numeric', 
                'diskon' => 'required|numeric', 
                'uangmuka' => 'required|numeric', 
                'pph' => 'required', 
                'ppn' => 'required', 
                'netto' => 'required|numeric'

            ]);

            $total = $data['total'];
            $diskon = $data['diskon'];
            $uangmuka = $data['uangmuka'];
            $netto = $data['netto'];

            $total = str_replace('.', '', $total);
            $total = (double)str_replace(',', '.', $total);
            $data['total'] = $total;
            $diskon = str_replace('.', '', $diskon);
            $diskon = (double)str_replace(',', '.', $diskon);
            $data['diskon'] = $diskon;
            $uangmuka = str_replace('.', '', $uangmuka);
            $uangmuka = (double)str_replace(',', '.', $uangmuka);
            $data['uangmuka'] = $uangmuka;
            $netto = str_replace('.', '', $netto);
            $netto = (double)str_replace(',', '.', $netto);
            $data['netto'] = $netto;

            $salesorder = SalesOrder::where('uuid', $uuid)->first();
            $data = $request->only(['profile_id', 'supplier_id', 'diketahui_oleh', 'jumlah_paket', 'bukti_pesan', 'jenix_pembayaran', 'account_id', 'tgl_pesan', 'tgl_pengiriman', 'tempo', 'keterangan', 'total', 'diskon', 'uangmuka', 'pph', 'ppn', 'netto']);

            if ($salesorder->update($data)) {
                SalesOrderDetail::where('salesorder_id', $salesorder->id)->delete();

                foreach ($request->detail['barangmentah'] as $item) {
                    $harga = $item['harga'];
                    $diskon = $item['diskon'];
                    $jumlah = $item['jumlah'];

                    $harga = str_replace('.', '', $harga);
                    $harga = (double)str_replace(',', '.', $harga);
                    $item['harga'] = $harga;
                    $diskon = str_replace('.', '', $diskon);
                    $diskon = (double)str_replace(',', '.', $diskon);
                    $item['diskon'] = $diskon;
                    $jumlah = str_replace('.', '', $jumlah);
                    $jumlah = (double)str_replace(',', '.', $jumlah);
                    $item['jumlah'] = $jumlah;

                    // $child = SatuanChild::find($data['satuan']);
                    // if ($data['volume'] > ) {
                    //     $volume = $data['volume'];
                    //     $volume = $volume * $child->nilai;
                    //     $data['volume'] = $volume;
                    // }
    
                        SalesOrderDetail::create([
                            'volume' => $item['volume'],
                            'barangmentah_id' => $item['barangmentah_id'],
                            'harga' => $item['harga'],
                            'diskon' => $item['diskon'],
                            'jumlah' => $item['jumlah'],
                            'keterangan' => isset($item['keterangan']) ? $item['keterangan'] : null,
                            'salesorder_id' => $salesorder->id,
    
                        ]);
                }

                foreach ($request->detail['barangjadi'] as $item) {
                    $harga = $item['harga'];
                    $diskon = $item['diskon'];
                    $jumlah = $item['jumlah'];
                    
                    $harga = str_replace('.', '', $harga);
                    $harga = (double)str_replace(',', '.', $harga);
                    $item['harga'] = $harga;
                    $diskon = str_replace('.', '', $diskon);
                    $diskon = (double)str_replace(',', '.', $diskon);
                    $item['diskon'] = $diskon;
                    $jumlah = str_replace('.', '', $jumlah);
                    $jumlah = (double)str_replace(',', '.', $jumlah);
                    $item['jumlah'] = $jumlah;

                    $child = SatuanJadiChild::find($item['satuan']);
                    $volume = $item['volume'] * $child->nilai;
                    $item['volume'] = $volume;

                        SalesOrderDetail::create([
                            'volume' => $item['volume'],
                            'barangjadi_id' => $item['barangjadi_id'],
                            'harga' => $item['harga'],
                            'diskon' => $item['diskon'],
                            'jumlah' => $item['jumlah'],
                            'keterangan' => isset($item['keterangan']) ? $item['keterangan'] : null,
                            'salesorder_id' => $salesorder->id,
    
                        ]);
                }

            return response()->json(['message' => 'Sales Order berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }
}

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            SalesOrder::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Sales Order berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getnumber(Request $request)
    {
        
        $a = SalesOrder::whereYear('tgl_pesan', date('Y', strtotime($request->tgl_pesan)))->pluck('no_pemesanan')->toArray();

        // return $a;
        
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

    public function generatepdf1($uuid)
    {
        $data = SalesOrder::with(['supplier', 'supplier.provinsi', 'supplier.kota', 'supplier.kecamatan', 'profile', 'profile.provinsi', 'profile.kecamatan', 'profile.kelurahan', 'profile.kota', 'diketahuioleh', 'detail', 'user'])->where('uuid', $uuid)->first();
        $no_pemesanan = $data['no_pemesanan'];
        $pdf = PDF::loadview('laporan.salesorderV1.Index', ['data' => $data]);
        return $pdf->download('Salesorder - ' . $no_pemesanan);
    }

    public function generatepdf2($uuid)
    {
        $user = User::find(auth()->user()->id);
        $data = SalesOrder::with(['supplier', 'supplier.provinsi', 'supplier.kota', 'supplier.kecamatan', 'profile', 'profile.provinsi', 'profile.kecamatan', 'profile.kelurahan', 'profile.kota', 'diketahuioleh', 'detail', 'user'])->where('uuid', $uuid)->first();
        $no_pemesanan = $data['no_pemesanan'];
        $pdf = PDF::loadview('laporan.salesorderV2.Index', ['data' => $data, 'user' => $user]);
        return $pdf->download('Salesorder - ' . $no_pemesanan);
    }
}
