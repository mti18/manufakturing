<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\Supplier;

class SalesOrderController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = SalesOrder::with(['supplier', 'profile', 'diketahui_oleh'])->where(function ($q) use ($request) {
                $q->where('profile_id', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('supplier_id', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);


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
                'profile_id' => 'required|numeric', 
                'supplier_id' => 'required|numeric', 
                'diketahui_oleh' => 'nullable|numeric', 
                'jumlah_paket' => 'nullable|string', 
                'bukti_pesan' => 'nullable|string', 
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer,Free', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'tempo' => 'nullable|numeric',
            ]);
            $data['status'] = '1';
            $data['pembayaran'] = ($request->tempo == '0') ? 'yes' : 'no';
            $data = SalesOrder::create($data);
            $data = SalesOrder::where('id', $data->id)->first();

            return response()->json(['message' => 'Sales Order berhasil diperbarui', 'data' => $data]);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = SalesOrder::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = SalesOrder::where('uuid', $uuid)->first();
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
                'jumlah_paket' => 'nullable|string', 
                'bukti_pesan' => 'required|string', 
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'tempo' => 'nullable|numeric',
            ]);
            SalesOrder::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Sales Order berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function updateMore(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'total' => 'required',
                'diskon' => 'nullable',
                'uang_muka' => 'nullable',
                'pph' => 'nullable',
                'ppn' => 'nullable',
                'netto' => 'required',
            ]);
            Position::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Sales Order berhasil diperbarui']);
        } else {
            return abort(404);
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
}
