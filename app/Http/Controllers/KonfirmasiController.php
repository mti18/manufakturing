<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;

class KonfirmasiController extends Controller
{
    public function paginateOrder(Request $request, $status) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));

            if ($status == 'process') {
                $yes = SalesOrderDetail::whereIn('status', ['0'])->pluck('salesorder_id')->toArray();
            }else{
                $not = SalesOrderDetail::whereIn('status', ['0'])->pluck('salesorder_id')->toArray();
                $yes = SalesOrderDetail::whereIn('status', ['1', '2', '3', '4', '5', '6'])
                ->whereNotIn('salesorder_id', $not)->pluck('salesorder_id')->toArray();
                
            }


            $courses = SalesOrder::with(['supplier', 'profile', 'detail', 'user'])->whereIn('id', $yes)->where(function ($q) use ($request) {
                $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function paginateOrderDetail(Request $request, $id) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);    

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));

            $courses = SalesOrderDetail::where('salesorder_id', $id)->where(function ($q) use ($request) {
                $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
            
        } else {
            return abort(404);
        }
    }
}
