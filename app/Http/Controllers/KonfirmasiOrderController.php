<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;

class KonfirmasiOrderController extends Controller
{
    public function paginate($uuid) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            $salesorder = SalesOrder::findByUuid($uuid);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = SalesOrderDetail::with('salesorder')->where('salesorder_id', $salesorder->id)->where(function ($q) use ($request) {
                $q->where('volume', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('harga', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);


            return response()->json($courses);
        } else {
            return abort(404);
        }
    }
}
