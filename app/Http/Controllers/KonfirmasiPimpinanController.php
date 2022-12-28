<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class KonfirmasiPimpinanController extends Controller
{
    public function paginateOrder(Request $request, $status)
    {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            if ($status == 'success') {                
                DB::statement(DB::raw('set @nomor=0+' . $page * $per));
                $courses = SalesOrder::with(['supplier', 'profile', 'detail', 'user'])->where('acc_pimpinan', 'Y')->where(function ($q) use ($request) {
                    $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
                })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);
            } else {
                DB::statement(DB::raw('set @nomor=0+' . $page * $per));
                $courses = SalesOrder::with(['supplier', 'profile', 'detail', 'user'])->where('acc_pimpinan', 'N')->where(function ($q) use ($request) {
                    $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
                })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);
                
            }

            

            return response()->json($courses);
            
        } else {
            return abort(404);
        }
    }

    // public function detailOrder($uuid)
    // {
        
    // }

    public function generatepdforder($uuid)
    {
        $data = SalesOrder::with(['supplier', 'supplier.provinsi', 'supplier.kota', 'supplier.kecamatan', 'profile', 'profile.provinsi', 'profile.kecamatan', 'profile.kelurahan', 'profile.kota', 'detail', 'user'])->where('uuid', $uuid)->first();
        $no_pemesanan = $data['no_pemesanan'];
        $pdf = PDF::loadview('laporan.konfirmasi.Index', ['data' => $data]);
        return $pdf->download('Konfirmasi Order - ' . $no_pemesanan);

    }

    public function acceptOrder($uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $salesOrder = SalesOrder::findByUuid($uuid);

            if(!isset($salesOrder)){
                return response()->json([
                    'status' => false,
                    'message' => 'Pembelian tidak ditemukan.'
                ], 404);
            }

            if ($salesOrder['acc_pimpinan'] != 'Y' ) {

                DB::beginTransaction();

                try{
                    $salesOrder->update([
                        'acc_pimpinan' => 'Y',
                        
                    ]);
                    
                    
                    DB::commit();
                    return response()->json(['message' => 'Berhasil di Konfirmasi']);
                } catch(Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message' => 'Oops, Terjadi kesalahan!'
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Order sudah dikonfirmasi',
                ]);
            }

        }else {
            return response()->json([
                'status' => false,
                'message' => '404 Not Found.'
            ], 404);

        }
    }
    
    public function revisiOrder($uuid)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $salesOrder = SalesOrder::findByUuid($uuid);

            if(!isset($salesOrder)){
                return response()->json([
                    'status' => false,
                    'message' => 'Order tidak ditemukan.'
                ], 404);
            }

            $cekhDetails = SalesOrderDetail::where('salesorder_id', $salesOrder->id)
            ->whereIn('status', ['3', '4', '5', '6'])->get();
            if(count($cekhDetails) != 0){
                return response()->json([
                    'status' => false,
                    'message' => 'Oops, Terjadi kesalahan!'
                ], 404);
            }        
            
            DB::beginTransaction();

            try{

                $salesOrder->update([
                    'status' => 'draft',
                    'acc_pimpinan' => 'N',
                ]);

                $details = SalesOrderDetail::where('salesorder_id', $salesOrder->id)->get();
                foreach ($details as $key => $vDetails) {
                    $detail = SalesOrderDetail::find($vDetails->id);
                    $detail->update([
                        'status' => '0',
                    ]);
                }
                
                DB::commit();
            } catch(Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => false,
                    'message' => 'Oops, Terjadi kesalahan!'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Order berhasil direvisi.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => '404 Not Found.'
            ], 404);
        }
    }

}
