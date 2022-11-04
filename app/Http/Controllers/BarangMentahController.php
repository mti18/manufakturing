<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\BarangKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangMentah;
use App\Models\SatuanChild;

class BarangMentahController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangMentah::where(function ($q) use ($request) {
                $q->where('nm_barangmentah', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

        
            $courses->map(function ($a)
            {
                $a->kategoribadges="";
                foreach($a->kategoris as $item){
                    $a->kategoribadges .= $item->nm_kategori . ', ';
                }
            });

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function child($id)
    {
        $data = SatuanChild::where('barangsatuan_id', $id)->get();
        return RestApi::success($data);
    }

    public function stok($id)
    {
        
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barangmentah' => 'required|string',
                'stok' => 'required|numeric',
                'satuan' => 'required',
                'barangsatuan_id' => 'required',
                'kategoris' => 'required|array',

                
            ]); 

            $child = SatuanChild::find($data['satuan']);
            
            $stok = $data['stok'];

            $stok = $stok * $child->nilai;
            
            $data['stok'] = $stok;
            
            // return RestApi::error('error', 404, $data);
            unset($data['satuan']);

            $data = BarangMentah::create($data);
            
            // return RestApi::error('ooi',404,count($request->kategoris));
            
            foreach($request->kategoris as $item){
                BarangKategori::create([
                    'kategori_id' => $item['id'],
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

            $child = SatuanChild::whereDoesntHave('children')->where('barangsatuan_id', $data->barangsatuan_id)->first();
            $data->satuan_id = $child->id;
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_barangmentah' => 'required|string',
                'stok' => 'required|numeric',
                'satuan' => 'required',
                'barangsatuan_id' => 'required',
            ]);

            $child = SatuanChild::find($data['satuan']);
            
            $stok = $data['stok'];

            $stok = $stok * $child->nilai;
            
            $data['stok'] = $stok;

            unset($request->satuan_id);
            unset($request->satuan);

           
            $data =  BarangMentah::where('uuid', $uuid)->first();

            // return RestApi::error('', 404, $data);
            if ($data->update($request->only(['stok', 'barangsatuan_id', 'nm_barangmentah']))) {

                BarangKategori::where('barang_mentah_id', $data->id)->delete();

                foreach($request->kategoris as $item){
                    BarangKategori::create([
                        'kategori_id' => $item['id'],
                        'barang_mentah_id' => $data->id
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
            BarangMentah::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Barang Mentah berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
