<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\BarangJadi;
use App\Models\BarangJadiKategori;
use App\Models\BarangSatuanJadi;
use App\Models\Gudang;
use App\Models\Rak;
use App\Models\SatuanJadiChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangJadiController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangJadi::with(['barangjadigudangs', 'rakbarangjadi', 'barangsatuanjadi'])->where(function ($q) use ($request) {
            $q->where('nm_barang_jadi', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('kd_barang_jadi', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);


            $courses->map(function ($a)
            {
                $a->kategoribadges="";
                foreach($a->barangjadikategoris as $item){
                    $a->kategoribadges .= $item->nm_kategori . ', ';
                }

                
                if(!empty($a->barangjadigudangs)){
                    $a->nm_gudang = $a->barangjadigudangs->nm_gudang;
                } else {
                    $a->nm_gudang = "Belum ada gudang";
                }

                if (($a->stok_bagus + $a->stok_jelek ) != 0) {
                    $a->stokbarang = $a->stok_bagus + $a->stok_jelek . ' ' . $a->barangsatuanjadi->child[0]->nm_satuan_jadi_children;
                } else {
                    $a->stokbarang = "Habis";
                }

                $a->nm_rak = $a->rakbarangjadi->nm_rak;

                $a->harga = $a->harga . ",00";
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }



    public function getharga(Request $request)
    {
        $data = BarangJadi::find($request->barangjadi_id);
        return RestApi::success($data);
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barang_jadi' => 'required|string',
                'barangsatuanjadi_id' => 'required',
                'rak_id' => 'required',
                'gudang_id' => 'required',
                'barangjadikategoris' => 'required|array',
                'kd_barang_jadi' => 'required|string',
                'foto' => 'required|image',
                'harga' => 'nullable',

            ]);


            $data['foto'] = 'storage/' . $request->foto->store('barangjadi', 'public');

            $harga = $data['harga'];
            $harga = str_replace('.', '', $harga);
            $harga = (double)str_replace(',', '.', $harga);
            $data['harga'] = $harga;


            $data = BarangJadi::create($data);
            
            foreach($request->barangjadikategoris as $item){
                BarangJadiKategori::create([
                    'kategori_id' => $item,
                    'barang_jadi_id' => $data->id,
                ]);
                // return RestApi::error('err',400,$item) ;
            }
            
            return response()->json(['message' => 'Barang Jadi berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = BarangJadi::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = BarangJadi::with(["barangjadigudangs", "barangjadigudangs.rak"])->where('uuid', $uuid)->first();

            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barang_jadi' => 'required|string',
                'barangsatuanjadi_id' => 'required',
                'gudang_id' => 'required',
                'rak_id' => 'required',
                'barangjadikategoris' => 'required|array',
                'kd_barang_jadi' => 'required|string',
                'foto' => 'required|image',
                'harga' => 'nullable',

            ]);
            
            
            $bj = BarangJadi::where('uuid', $uuid)->first();
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $bj->foto)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $bj->foto)));
            }
            
            $barangj = BarangJadi::where('uuid', $uuid)->first();
            $data = $request->only(['stok', 'barangsatuanjadi_id', 'nm_barangjadi', 'gudang_id', 'kd_barang_jadi', 'rak_id', 'harga']);
            $data['foto'] = 'storage/' . $request->foto->store('barangjadi', 'public');
            
            $harga = $data['harga'];
            $harga = str_replace('.', '', $harga);
            $harga = (double)str_replace(',', '.', $harga);
            $data['harga'] = $harga;

            if ($barangj->update($data)) {

                BarangJadiKategori::where('barang_jadi_id', $barangj->id)->delete();

                foreach($request->barangjadikategoris as $item){
                    BarangJadiKategori::create([
                        'barang_jadi_id' => $barangj->id,
                        'kategori_id' => $item
                    ]); 
                }
        }

            return response()->json(['message' => 'Barang Jadi berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $barangjadi =  BarangJadi::where('uuid', $uuid)->first();
            $barangjadi->delete();
            return response()->json(['message' => 'Barang Jadi berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getcode()
    {
        $data = BarangJadi::pluck('kd_barang_jadi')->toArray();
        $a = [];

        foreach($data as $item){
              $exp = explode("-", $item);
              $a[] = $exp[1];
        }

        
        if(count($a) > 0){
            sort($a);
            $start = 1;
            for ($i=0; $i < count($a); $i++) { 
                if((int)$a[$i] != $start){
                    return str_pad($start,4,"0",STR_PAD_LEFT);
                }
                $start++;
            }
            return str_pad($start,4,"0",STR_PAD_LEFT);
            
        }
        return str_pad('1',4,"0",STR_PAD_LEFT);
    }

    public function getcodebyid($id)
    {
        $data = BarangJadi::findByUuid($id)->kd_barang_jadi;
        $exp = explode("-",$data);
        return $exp[1];
    }
}
