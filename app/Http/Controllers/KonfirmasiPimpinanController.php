<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonfirmasiPimpinanController extends Controller
{
    public function paginateOrder(Request $request)
    {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = SalesOrder::with(['supplier', 'profile', 'diketahuioleh', 'detail', 'user'])->where(function ($q) use ($request) {
                $q->where('no_pemesanan', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('profile_id', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('supplier_id', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
            
        } else {
            return abort(404);
        }
    }

    public function detailOrder()
    {
        
    }

    public function revisiOrder()
    {
        
    }

    public function acceptOrder()
    {
        
    }
}
