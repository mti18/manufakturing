<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Asset::where(function ($q) use ($request) {
                $q->where('nm_asset', 'LIKE', '%'.$request->search.'%')
                ->orWhere('price', 'LIKE', '%'.$request->search.'%')
                ->orWhere('number', 'LIKE', '%'.$request->search.'%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function($a) {
                $a->price = AppHelper::rupiah($a->price, 2);
                $a->action = '<button class="btn btn-sm btn-icon btn-light-primary mb-2 edit" title="Edit" data-id="'.$a->uuid.'"><i class="fa fa-pencil-alt"></i></button> <button class="btn btn-sm btn-icon btn-light-danger mb-2 delete" title="Hapus" data-id="'.$a->uuid.'"><i class="fa fa-trash"></i></button>';
               return $a;
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_asset'  => 'required|string',
                'number'  => 'required|string',
                'price'  => 'required|string',
                'golongan_id'  => 'required|integer',
                'asset_group_id'  => 'required|integer',

            ]);
            
            $data['price'] = str_replace('.', '', $request['price']);
            $data['price'] = (double)str_replace(',', '.', $request['price']);
 
           
            $data = Asset::create($data);

            return response()->json(['message' => ' Asset berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson( ) && request()->ajax()) {
            $Asset = Asset::get();
            
            return response()->json([
                'status'    => true,
                'data'      => $Asset
            ], 200);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $Asset = Asset::findByUuid($uuid);
            $Asset->price = number_format($Asset->price, 2, '.', '');
            return response()->json($Asset);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_asset'  => 'required|string',
                'number'  => 'required|string',
                'price'  => 'required|string',
                'golongan_id'  => 'required|integer',
                'asset_group_id'  => 'required|integer',

            ]);
            $data = Asset::findByUuid($uuid);
            
         
            $data->update($request->all());
     

            return response()->json(['message' => ' Asset berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Asset::where('uuid', $uuid)->delete();
            return response()->json(['message' => ' Asset berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
