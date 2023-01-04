<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\BuktiHutang;
use App\Models\HutangPiutang;
use App\Models\HutangPiutangDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HutangPiutangController extends Controller
{
    public function paginateHutang(Request $request, )
    {

		if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            // Get User By Search And Per Page
            DB::statement(DB::raw('set @angka=0+' . $page * $per));
            $data = HutangPiutang::with('account', 'profile')
					->where('type', 'Hutang')
					->where(function($query) use ($request) {
                $query->whereHas('account', function($query) use ($request){
                    $query->where('nm_account', 'LIKE', '%'.$request->search.'%');
                })->orWhereHas('profile', function($query) use ($request){
                    $query->where('nama', 'LIKE', '%'.$request->search.'%');
                })->orWhere('jumlah', 'LIKE', '%'.$request->search.'%')
                ->orWhere('tanggal', 'LIKE', '%'.$request->search.'%');
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
    public function paginatePiutang(Request $request, )
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
			
			
						$filtered = collect([]);
						$filtered->push($data->where('salesorder_id', null)->where('pembelian_id', null)->unique('account_id')->all());
						$filtered->push($data->filter(function ($item) {
							return $item->salesorder_id != null || $item->pembelian_id != null;
						})->unique('account_id')->all());

						$data = $filtered->flatten()->sortByDesc('created_at')->map(function ($a) {
							$a->jumlah = HutangPiutang::where('account_id', $a->account_id)->where('type', 'Hutang')->where(function ($q) use ($a) {
								if (!isset($a->salesorder_id) && !isset($a->pembelian_id)) {
									$q->where('salesorder_id', null)->where('pembelian_id', null);
								} else {
									$q->where('salesorder_id', '!=', null)->orWhere('pembelian_id', '!=', null);
								}
							})->sum('jumlah');
							
							// Collection to Object
							return (object)collect($a)->reject(function(){})->all();
						});

						$data = AppHelper::paginateCollection($data, $per);


					
				$data->map(function($a){
					$a->tanggal = AppHelper::tgl_indo($a->tanggal);
			
										$items = HutangPiutang::where('account_id', $a->account_id)->where('type', 'Hutang')->where(function($q) use($a) {
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
	public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
				'profile_id' => 'required|integer',
				'account_id' => 'required|integer',
				'tanggal' => 'required|string',
				'jumlah' => 'required|string',
				'tempo' => 'required|integer',
				'keterangan' => 'nullable|string',
				'bukti'  => 'required|array',
                'bukti.*' => 'file',

            ]);
			$data['type'] = 'Hutang';
			$jml = $data['jumlah'];
			$jml = str_replace('.', '', $jml);
            $jml = (double)str_replace(',', '.', $jml);
            $data['jumlah'] = $jml;

        	$data = HutangPiutang::create($data);
			
			foreach ($request->bukti as $file) {
                $bukti['hutangpiutang_id'] = $data->id;
                $bukti['bukti'] = 'storage/' . $file->store('buktihutangpiutang', 'public');
                BuktiHutang::create($bukti);
                # code...
            }

            return response()->json(['message' => 'Hutang berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }
	public function editHutang($uuid)
    {
    	if (request()->wantsJson() && request()->ajax()) {
    	    $data = HutangPiutang::findByUuid($uuid);
					$data->jumlah = HutangPiutang::where('account_id', $data->account_id)->where('type', 'Hutang')->where(function ($q) use ($data) {
						if (!isset($data->salesorder_id) && !isset($data->pembelian_id)) {
							$q->where('salesorder_id', null)->where('pembelian_id', null);
						} else {
							$q->where('salesorder_id', '!=', null)->orWhere('pembelian_id', '!=', null);
						}
					})->get()->sum('jumlah');
    	    $data->jumlah = number_format($data->jumlah,2,',','.');

    	    return response()->json([
    	        'status' => true,
    	        'data' => $data,
    	        'message' => 'Data berhasil di ambil'
    	    ]);
    	}else{
    	    return response()->json([
    	        'status' => false,
    	        'message' => '404 Not Found.'
    	    ], 404);
    	}
    }


}
