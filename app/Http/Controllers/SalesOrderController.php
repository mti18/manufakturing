<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;


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
            //         '<span class="label label-danger label-pill label-inline mr-2">Draft</span>';
            //     }
            //     elseif ($a->status=='process') {
            //         '<span class="label label-danger label-pill label-inline mr-2">Process</span>';
            //     }
            //     elseif ($a->staus=='ready') {
            //         '<span class="label label-danger label-pill label-inline mr-2">Ready</span>';
            //     }
            //     else {
            //         '<span class="label label-danger label-pill label-inline mr-2">Success</span>';
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
                'bukti_pesan' => 'required|string', 
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer,Free', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'tempo' => 'nullable|numeric',
            ]);
            $request->merge([
                'status' => '1',
                'pembayaran' => ($request->tempo == '0') ? 'yes' : 'no',
                
                // 'kode' => $this->getCode($request->parent_id)
            ]);
            SalesOrder::create($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
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
                'jenis_pembayaran' => 'required|in:Tunai,Cek,Transfer,Free', 
                'account_id' => 'nullable|numeric',  
                'tgl_pesan' => 'required|string', 
                'tgl_pengiriman' => 'required|string', 
                'tempo' => 'nullable|numeric',
            ]);
            SalesOrder::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function updateMore(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'name' => 'required',
                'code' => 'required',
            ]);
            Position::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            SalesOrder::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Jabatan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}