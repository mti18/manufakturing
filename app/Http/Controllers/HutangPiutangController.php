<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\HutangPiutang;
use App\Models\HutangPiutangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HutangPiutangController extends Controller
{
    public function paginate(Request $request, )
    {

		if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            // Get User By Search And Per Page
            DB::statement(DB::raw('set @angka=0+' . $page * $per));
            $data = HutangPiutang::with('account', 'profile')
					->where('type')
					->where(function($query) use ($request) {
                $query->whereHas('account', function($query) use ($request){
                    $query->where('nm_account', 'LIKE', '%'.$request->search.'%');
                })->orWhereHas('profile', function($query) use ($request){
                    $query->where('nama', 'LIKE', '%'.$request->search.'%');
                })->orWhere('jumlah', 'LIKE', '%'.$request->search.'%')
                ->orWhere('tanggal', 'LIKE', '%'.$request->search.'%')
                ->orWhere('bukti', 'LIKE', '%'.$request->search.'%');
            })->orderBy('tanggal', 'desc')->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);

					
				$data->map(function($a){
					$a->tanggal = AppHelper::tgl_indo($a->tanggal);
			
										$items = HutangPiutang::where('account_id', $a->account_id)->where('type', $a->type)->where(function($q) use($a) {
											if (!isset($a->salesorder_id) && !isset($a->pembelian_id)) {
												$q->where('salesorder_id', null)->where('pembelian_id', null);
											} else {
												$q->where('salesorder_id', '!=', null)->orWhere('pembelian_id', '!=', null);
											}
										})->pluck('id')->all();
										$bayar = HutangPiutangDetail::whereIn('hutangpiutang_id', $items)->get()->sum('jumlah');
										
										$a->sisabayar = number_format($a->jumlah-$bayar,2,',','.');
							$a->jumlah = number_format($a->jumlah,2,',','.');
							$a->tempo = $a->tempo.' Hari';
			
							// Button Action
										$btnMoney = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm money" title="Bayar" data-uuid="'.$a->uuid.'" data-id="'.$a->id.'" data-sisabayar="'.$a->sisabayar.'" data-account="'.$a->account_id.'"><i class="la la-money kt-font-orange"></i></button>';
										$btnEdit = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm edit" title="Edit" data-uuid="'.$a->uuid.'" data-id="'.$a->id.'"><i class="la la-edit kt-font-warning"></i></button>';
										$btnHapus = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm delete" title="Hapus" data-uuid="'.$a->uuid.'" data-id="'.$a->id.'"><i class="la la-trash kt-font-danger"></i></button>';
										$btnDetail = '<button class="btn btn-sm btn-clean btn-icon btn-icon-sm detail" title="Detail" data-uuid="'.$a->uuid.'" data-id="'.$a->id.'"><i class="la la-eye kt-font-info"></i></button>';
			
										if ($a->sisabayar == "0,00") {
											$btnMoney = '';
										}
			
										if($a->salesorder_id == null && $a->pembelian_id == null){
											$a->action = $btnMoney.$btnEdit.$btnHapus.$btnDetail;
										}else{
											$a->action = $btnDetail;
										}


          
                return $a;
            });
            return response()->json($data);
        } else {
            abort(404);
        }
    	
    }

}
