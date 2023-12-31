<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangMentah;
use App\Models\BarangMentahKategori;
use App\Models\Gudang;
use App\Models\Rak;
use App\Models\SatuanChild;

class BarangMentahController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangMentah::with(['barangmentahgudangs','rakbarangmentah', 'barangsatuan'])->where(function ($q) use ($request) {
                $q->where('nm_barangmentah', 'LIKE', '%' . $request->search . '%');
                $q->orwhere('kd_barang_mentah', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            
            $courses->map(function ($a)
            {
                $a->kategoribadges="";
                foreach($a->barangmentahkategoris as $item){
                    $a->kategoribadges .= $item->nm_kategori . ', ';
                }

                if(!empty($a->barangmentahgudangs)){
                    $a->nm_gudang = $a->barangmentahgudangs->nm_gudang;
                } else {
                    $a->nm_gudang = "Belum ada gudang";
                }

                if ($a->stok != 0) {
                    $a->stokbarang = $a->stok . ' ' . $a->barangsatuan->child[6]->nm_satuan_children;
                } else {
                    $a->stokbarang = "Habis";
                }
                
                $a->nm_rak = $a->rakbarangmentah->nm_rak;

                $a->harga = $a->harga . ",00";


            });
            

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }
    
    public function getharga(Request $request)
    {
        $data = BarangMentah::find($request->barangmentah_id);
        return RestApi::success($data);
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barangmentah' => 'required|string',
                'barangsatuan_id' => 'required',
                'gudang_id' => 'required',
                'rak_id' => 'required',
                'barangmentahkategoris' => 'required|array',
                'kd_barang_mentah' => 'required|string',    
                'foto' => 'required|image',
                'harga' => 'nullable',   
 
            ]); 

            $data['foto'] = 'storage/' . $request->foto->store('barangmentah', 'public');

            $harga = $data['harga'];
            $harga = str_replace('.', '', $harga);
            $harga = (double)str_replace(',', '.', $harga);
            $data['harga'] = $harga;

            $data = BarangMentah::create($data);
            
            // return RestApi::error('ooi',404,count($request->kategoris));
            
            foreach($request->barangmentahkategoris as $item){
                BarangMentahKategori::create([
                    'kategori_id' => $item,
                    'barang_mentah_id' => $data->id
                ]);
            }

            return response()->json(['message' => 'Barang Mentah berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = BarangMentah::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = BarangMentah::where('uuid', $uuid)->first();

            // $rak = Rak::where('gudang_id', $data->gudang_id)->first();
            // $data->rak_id = $rak->id;
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barangmentah' => 'required|string',
                'barangsatuan_id' => 'required',
                'gudang_id' => 'required',
                'rak_id' => 'required',
                'barangmentahkategoris' => 'required|array',
                'kd_barang_mentah' => 'required|string',
                'foto' => 'required|image',
                'harga' => 'nullable',   
    
            ]);
                        
            $bm = BarangMentah::where('uuid', $uuid)->first();
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $bm->foto)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $bm->foto)));
            }

            $barangm =  BarangMentah::where('uuid', $uuid)->first();
            $data = $request->only(['stok', 'barangsatuan_id', 'nm_barangmentah', 'gudang_id', 'kd_barang_mentah', 'rak_id', 'harga']);
            $data['foto'] = 'storage/' . $request->foto->store('barangmentah', 'public');

            $harga = $data['harga'];
            $harga = str_replace('.', '', $harga);
            $harga = (double)str_replace(',', '.', $harga);
            $data['harga'] = $harga;

            if ($barangm->update($data)) {

                BarangMentahKategori::where('barang_mentah_id', $barangm->id)->delete();

                foreach($request->barangmentahkategoris as $item){
                    BarangMentahKategori::create([
                        'kategori_id' => $item,
                        'barang_mentah_id' => $barangm->id
                    ]);
                }

            }
            

            return response()->json(['message' => 'Barang Mentah berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $barangmentah = BarangMentah::where('uuid', $uuid)->first();
            $barangmentah->delete();
            return response()->json(['message' => 'Barang Mentah berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    
    public function getcode()
    {
        $data = BarangMentah::pluck('kd_barang_mentah')->toArray();
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
        $data = BarangMentah::findByUuid($id)->kd_barang_mentah;
        $exp = explode("-",$data);
        return $exp[1];
    }
}

