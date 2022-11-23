<?php

namespace App\Http\Controllers;

use App\Models\BarangJadi;
use App\Models\BarangMentah;
use App\Models\BarangProduksi;
use App\Models\BarangProduksiBarangMentah;
use App\Models\BarangSatuanJadi;
use App\Models\SatuanChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangProduksiController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = BarangProduksi::where(function ($q) use ($request) {
                $q->where('barangjadi_id', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function stok($id)
    {
        
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'stok_jadi' => 'required|numeric',
                'barangjadi_id' => 'required',
                'barangproduksibarangmentahs' => 'required|array',
            ]);
            
            $data['barangjadi'] = BarangJadi::where('id', $request->barangjadi_id)->first()->id;

            $data = BarangProduksi::create($data);

            foreach($request->barangproduksibarangmentahs as $item){
                $child = SatuanChild::find($item['satuan_id']);

                $stok = $item['stok_digunakan'] * $child->nilai;

                BarangProduksiBarangMentah::create([
                    'barang_mentah_id' => $item['barang_mentah_id'],
                    'stok_digunakan' => $stok,
                    'barang_produksi_id' => $data->id
                ]);
            }


            
            return response()->json(['message' => 'Barang Produksi berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = BarangProduksi::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = BarangProduksi::with('barangproduksibarangmentahs')->where('uuid', $uuid)->first();

   
            $data->barangproduksibarangmentahs->map(function($q){
                $data = BarangMentah::where('id', $q->barang_mentah_id)->first();
                $q->satuan_child = SatuanChild::where('barangsatuan_id', $data->barangsatuan_id)->get();
                $child = SatuanChild::whereDoesntHave('children')->where('barangsatuan_id', $data->barangsatuan_id)->first();
                $q->satuan_id = $child->id;
            });
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'stok_jadi' => 'required|numeric',
                'barangjadi_id' => 'required',
                'barangproduksibarangmentahs' => 'required|array',

            ]);

            $data['barangjadi'] = BarangJadi::where('id', $request->barangjadi_id)->first()->id;

            $barangp = BarangProduksi::where('uuid', $uuid)->first();
            $data = $request->only(['stok_jadi', 'barangjadi_id']);

            if ($barangp->update($data)) {

                foreach($request->barangproduksibarangmentahs as $item){
                $child = SatuanChild::find($item['satuan_id']);

                $stok = $item['stok_digunakan'] * $child->nilai;

                BarangProduksiBarangMentah::where('barang_produksi_id', $barangp->id)->delete();

                BarangProduksiBarangMentah::create([
                    'barang_mentah_id' => $item['barang_mentah_id'],
                    'stok_digunakan' => $stok,
                    'barang_produksi_id' => $barangp->id
                ]);
            }
        }

            return response()->json(['message' => 'Barang Produksi berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $barangproduksi =  BarangProduksi::where('uuid', $uuid)->first();
            $barangproduksi->delete();
            return response()->json(['message' => 'Barang Produksi berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}