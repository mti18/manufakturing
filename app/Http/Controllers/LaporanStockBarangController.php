<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\BarangJadi;
use App\Models\BarangMentah;
use App\Models\StokKeluar;
use App\Models\StokMasuk;
use Illuminate\Http\Request;

class LaporanStockBarangController extends Controller
{
    public function indexStockJadi(Request $request, $bulan, $tahun)
    {   
        if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);

            // Get User By Search And Per Page
            $barangMasuk = StokMasuk::whereMonth('tanggal_masuk', $bulan)->whereYear('tanggal_masuk', $tahun)
            ->pluck('barangjadi_id')->unique()->toArray();
            $barangKeluar = StokKeluar::whereMonth('tanggal_keluar', $bulan)->whereYear('tanggal_keluar', $tahun)
            ->pluck('barangjadi_id')->unique()->toArray();

            $barangId = array_unique(array_merge($barangMasuk, $barangKeluar));

            $data = BarangJadi::with(['barangjadigudangs' => function($query){
                $query->select('id', 'nm_gudang');
            }])->whereIn('id', $barangId)
            ->where(function($query) use ($request){
                $query->whereHas('barangjadigudangs', function($query) use ($request){
                    $query->where('nm_gudang', 'LIKE', '%'.$request->search.'%');
                })->orWhere('nm_barang_jadi', 'LIKE', '%'.$request->search.'%')
                ->orWhere('kd_barang_jadi', 'LIKE', '%'.$request->search.'%');
            })->orderBy('id', 'desc')->paginate($per);

            $data->map(function($a){
                $a->action = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm cetak" title="Cetak Faktur Penjualan" data-uuid="'.$a->uuid.'"><i class="la la-file-pdf-o kt-font-success"></i></button> <button class="btn btn-sm btn-clean btn-icon btn-icon-sm detail" title="Detail" data-uuid="'.$a->uuid.'"><i class="la la-eye kt-font-info"></i></button>';

                return $a;
            });

            return response()->json($data);
        }else{
            abort(404);
        }
    }
    public function indexStockMentah(Request $request, $bulan, $tahun)
    {   
        if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);

            // Get User By Search And Per Page
            $barangMasuk = StokMasuk::whereMonth('tanggal_masuk', $bulan)->whereYear('tanggal_masuk', $tahun)
            ->pluck('barangmentah_id')->unique()->toArray();
            $barangKeluar = StokKeluar::whereMonth('tanggal_keluar', $bulan)->whereYear('tanggal_keluar', $tahun)
            ->pluck('barangmentah_id')->unique()->toArray();

            $barangId = array_unique(array_merge($barangMasuk, $barangKeluar));

            $data = BarangMentah::with(['barangmentahgudangs' => function($query){
                $query->select('id', 'nm_gudang');
            }])->whereIn('id', $barangId)
            ->where(function($query) use ($request){
                $query->whereHas('barangmentahgudangs', function($query) use ($request){
                    $query->where('nm_gudang', 'LIKE', '%'.$request->search.'%');
                })->orWhere('nm_barangmentah', 'LIKE', '%'.$request->search.'%')
                ->orWhere('kd_barang_mentah', 'LIKE', '%'.$request->search.'%');
            })->orderBy('id', 'desc')->paginate($per);

            $data->map(function($a){
                $a->action = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm cetak" title="Cetak Faktur Penjualan" data-uuid="'.$a->uuid.'"><i class="la la-file-pdf-o kt-font-success"></i></button> <button class="btn btn-sm btn-clean btn-icon btn-icon-sm detail" title="Detail" data-uuid="'.$a->uuid.'"><i class="la la-eye kt-font-info"></i></button>';

                return $a;
            });

            return response()->json($data);
        }else{
            abort(404);
        }
    }
    public function detailStockBarangJadi($uuid, $bulan, $tahun)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $barang = BarangJadi::with(['barangsatuanjadi' => function($query){
                $query->select('id', 'nm_satuan_jadi');
            }])->where('uuid', $uuid)->first();

            $barangMasukLalu = StokMasuk::whereMonth('tanggal_masuk', $bulan)->whereYear('tanggal_masuk', $tahun)
            ->pluck('barangjadi_id')->unique()->toArray();
            $barangKeluarLalu = StokKeluar::whereMonth('tanggal_keluar', $bulan)->whereYear('tanggal_keluar', $tahun)
            ->pluck('barangjadi_id')->unique()->toArray();

            $barangMasuk = StokMasuk::with(['pembelian' => function($query){
                $query->with(['supplier' => function($query){
                    $query->select('id', 'nama');
                }])->select('id', 'supplier_id');
            }, 'barang' => function($query){
                $query->with(['satuan' => function($query){
                    $query->select('id', 'nm_satuan');
                }])->select('id', 'nm_barang', 'satuan_id');
            }])->where('barang_id', $barang->id)
            ->whereMonth('tanggal_masuk', $bulan)->whereYear('tanggal_masuk', $tahun)->get();

            $barangMasuk->map(function($a){
                $a->type = 'Barang Masuk';
                $a->tanggal = AppHelper::tgl_indo(date('Y-m-d', strtotime($a->created_at)));
                $a->barang_masuk = floatval($a->barang_masuk);
                $a->stock = floatval($a->stock_terakhir+$a->barang_masuk);

                $a->kdetail = KedatanganDetail::with('kedatangan')
                ->where('permintaan_detail_id', $a->permintaan_detail_id)->first();

                return $a;
            });

            $barangKeluar = StokKeluar::with(['salesorder' => function($query){
                $query->with(['supplier' => function($query){
                    $query->select('id', 'nama');
                }])->select('id', 'no_pemesanan', 'profile_id', 'supplier_id');
            }, 'barang' => function($query){
                $query->with(['satuan' => function($query){
                    $query->select('id', 'nm_satuan');
                }])->select('id', 'nm_barang', 'satuan_id');
            }])->where('barang_id', $barang->id)
            ->whereBetween('created_at', [$dari.' 00:00', $sampai.' 23:59'])->get();

            $barangKeluar->map(function($a){
                $a->type = 'Barang Keluar';
                $a->tanggal = AppHelper::tgl_indo(date('Y-m-d', strtotime($a->created_at)));
                $a->barang_keluar = floatval($a->barang_keluar);
                $a->stock = floatval($a->stock_terakhir-$a->barang_keluar);

                $a->kdetail = KedatanganDetail::with('kedatangan')
                ->where('permintaan_detail_id', $a->permintaan_detail_id)->first();

                return $a;
            });

            // Merge 2 Table
            $data = array_merge($barangMasuk->toArray(), $barangKeluar->toArray());
            usort($data, function($a, $b){
                return $a['created_at'] > $b['created_at'];
            });

            $total = $barangMasukLalu-$barangKeluarLalu;
            // Change stok
            for ($i=0; $i<count($data); $i++) {
                if($data[$i]['type'] == 'Barang Masuk'){
                    $total = $total+floatval($data[$i]['barang_masuk']);
                    $data[$i]['stock'] = $total;
                }else{
                    $total = $total-floatval($data[$i]['barang_keluar']);
                    $data[$i]['stock'] = $total;
                }
            }

            return response()->json([
                'data' => $data,
                'barang' => $barang,
                'total_sebelum' => $barangMasukLalu-$barangKeluarLalu,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => '404 Not Found.'
            ], 404);
        }
    }

}
