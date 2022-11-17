<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\BarangJadi;
use App\Models\BarangJadiKategori;
use App\Models\BarangSatuanJadi;
use App\Models\Gudang;
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
            $courses = BarangJadi::with(['barangjadigudangs', 'barangsatuanjadi'])->where(function ($q) use ($request) {
                $q->where('nm_barang_jadi', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('kd_barang_jadi', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);


            $courses->map(function ($a)
            {
                $a->kategoribadges="";
                foreach($a->barangjadikategoris as $item){
                    $a->kategoribadges .= $item->nm_kategori . ', ';
                }

                $a->stokbarang = $a->stok . ' ' . $a->barangsatuanjadi->nm_satuan_jadi;

                if(!empty($a->barangjadigudangs)){
                    $a->nm_gudang = $a->barangjadigudangs->nm_gudang;
                } else {
                    $a->nm_gudang = "Belum ada gudang";
                }
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function child($id)
    {
        $data = SatuanJadiChild::where('barangsatuanjadi_id', $id)->get();
        return RestApi::success($data);
    }

    public function stok($id)
    {
        
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barang_jadi' => 'required|string',
                'stok' => 'required|numeric',
                'barangsatuanjadi_id' => 'required',
                'satuan' => 'required',
                'gudang_id' => 'required',
                'barangjadikategoris' => 'required|array',
                'kd_barang_jadi' => 'required|string',
                'foto' => 'required|image',
            ]);

            $child = SatuanJadiChild::find($data['satuan']);
            
            $stok = $data['stok'];

            $stok = $stok * $child->nilai;
            
            $data['stok'] = $stok;
            
            unset($data['satuan']);


            $data['barangsatuanjadi'] = BarangSatuanJadi::where('id', $request->barangsatuanjadi_id)->first()->id;

            $data['gudang'] = Gudang::where('id', $request->gudang_id)->first()->id;

            $data['foto'] = 'storage/' . $request->foto->store('barangjadi', 'public');

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
            $data = BarangJadi::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barang_jadi' => 'required|string',
                'stok' => 'required|numeric',
                'satuan' => 'required',
                'barangsatuanjadi_id' => 'required',
                'gudang_id' => 'required',
                'barangjadikategoris' => 'required|array',
                'kd_barang_jadi' => 'required|string',
                'foto' => 'required|image',

            ]);

            $child = SatuanJadiChild::find($data['satuan']);
            
            $stok = $data['stok'];

            $stok = $stok * $child->nilai;
            
            $data['stok'] = $stok;
            
            unset($data['satuan']);

            $data['barangsatuanjadi'] = BarangSatuanJadi::where('id', $request->barangsatuanjadi_id)->first()->id;

            $data['gudang'] = Gudang::where('id', $request->gudang_id)->first()->id;

            $bj = BarangJadi::where('uuid', $uuid)->first();
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $bj->foto)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $bj->foto)));
            }


            $barangj = BarangJadi::where('uuid', $uuid)->first();
            $data = $request->only(['stok', 'barangsatuanjadi_id', 'nm_barangjadi', 'gudang_id', 'kd_barang_jadi']);
            $data['foto'] = 'storage/' . $request->foto->store('barangjadi', 'public');

            if ($barangj->update($data)) {

                BarangJadiKategori::where('barang_jadi_id', $barangj->id)->delete();

                foreach($request->barangjadikategoris as $item){
                    BarangJadiKategori::create([
                        'barang_jadi_id' => $barangj->id,
                        'kategori_id' => $item
                    ]); 
                    // return RestApi::error('err',400,$item) ;
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
