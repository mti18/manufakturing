<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use App\Models\BarangJadi;
use App\Models\BarangMentah;
use App\Models\SatuanChild;
use App\Models\SatuanJadiChild;
use App\Models\StokMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = StokMasuk::with(['barang_jadi.barangjadigudangs', 'barang_jadi.barangsatuanjadi','barang_mentah.barangmentahgudangs', 'barang_mentah.barangsatuan' ])->where(function ($q) use ($request) {
                $q->where('tipe_barang', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'desc')->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            $courses->map(function ($a)
            {
                if ($a->tipe_barang == "barang_mentah") {
                    $a->nm_barang = $a->barang_mentah->nm_barangmentah;
                    $a->gudang = $a->barang_mentah->barangmentahgudangs->nm_gudang;
                    // $a->barang_terakhir = $a->barang_mentah->stok - $a->barang_masuk . " " . $a->barang_mentah->barangsatuan->child[6]->nm_satuan_children;
                    $a->barang_masuk = $a->barang_masuk. " " . $a->barang_mentah->barangsatuan->child[6]->nm_satuan_children;
                } elseif ($a->tipe_barang == "barang_jadi") {
                    $a->nm_barang = $a->barang_jadi->nm_barang_jadi;
                    $a->gudang = $a->barang_jadi->barangjadigudangs->nm_gudang;
                    // $a->barang_terakhir = $a->barang_jadi->stok_bagus + $a->barang_jadi->stok_jelek - $a->barang_masuk . " Buah";
                    $a->barang_masuk = $a->barang_masuk . " Buah";
                
                }

                if ($a->kualitas == "bagus") {
                    $a->kualitas = 'Bagus';
                } else {
                    $a->kualitas = 'Jelek';
                }

                if ($a->tipe_barang == "barang_mentah") {
                    $a->tipe_barang ='Barang Mentah';
                } elseif ($a->tipe_barang == "barang_jadi") {
                    $a->tipe_barang ='Barang Jadi';
                }

                if (!empty($a->keterangan)) {
                    $a->keterangan = $a->keterangan;
                } else {
                    $a->keterangan = '-';
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

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tipe_barang' => 'required',
                'barangjadi_id' => 'nullable',
                'barangmentah_id' => 'nullable',
                'kualitas' => 'required',
                'tanggal_masuk' => 'nullable',
                'barang_masuk' => 'required|numeric',
                // 'stok_terakhir' => 'nullable',
                'keterangan' => 'nullable',
                'satuan_jadi' => 'nullable',
                'satuan_mentah' => 'nullable',
            ]);
            $tipe_barang = $data['tipe_barang'];

            if ($tipe_barang == "barang_mentah") {
                $child = SatuanChild::find($data['satuan_mentah']);
            
                $barang_masuk = $data['barang_masuk'];

                $barang_masuk = $barang_masuk * $child->nilai;

                $data['barang_masuk'] = $barang_masuk;
            } elseif ($tipe_barang == "barang_jadi") {
                $child = SatuanJadiChild::find($data['satuan_jadi']);
                
                $barang_masuk = $data['barang_masuk'];
                
                $barang_masuk = $barang_masuk * $child->nilai;
                
                $data['barang_masuk'] = $barang_masuk;
            }
            unset($data['satuan_jadi']);
            unset($data['satuan_mentah']);

            // if ($tipe_barang == "barang_mentah") {
            //     $stok = BarangMentah::find($request->barangmentah_id)->stok;
            //     $data['stok_terakhir'] = $stok;
            // } elseif ($tipe_barang == "barang_jadi") {
            //     $stok_bagus = BarangJadi::find($request->barangjadi_id)->stok_bagus;
            //     $stok_jelek = BarangJadi::find($request->barangjadi_id)->stok_jelek;
            //     $data['stok_terakhir'] = $stok_bagus + $stok_jelek;
            // }
            
            
            StokMasuk::create($data);

            return response()->json(['message' => 'Stok Masuk berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = StokMasuk::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = StokMasuk::with('barang_mentah', 'barang_mentah.barangsatuan', 'barang_mentah.barangsatuan.child', 'barang_jadi', 'barang_jadi.barangsatuanjadi', 'barang_jadi.barangsatuanjadi.child')->where('uuid', $uuid)->first();

            $tipe_barang = $data['tipe_barang'];
            if ($tipe_barang == "barang_jadi") {
                $data->child = $data->barang_jadi->barangsatuanjadi->child;
    
                $satuanjadi = SatuanJadiChild::where('barangsatuanjadi_id', $data->barang_jadi->barangsatuanjadi_id)->orderBy('nilai')->first()->id;
                $data->satuan_jadi = $satuanjadi;
            } else {
                $data->child = $data->barang_mentah->barangsatuan->child;
    
                $satuanmentah = SatuanChild::where('barangsatuan_id', $data->barang_mentah->barangsatuan_id)->orderBy('nilai')->first()->id;
                $data->satuan_mentah = $satuanmentah;
            }


            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'tipe_barang' => 'required',
                'barangjadi_id' => 'nullable',
                'barangmentah_id' => 'nullable',
                'kualitas' => 'required',
                'tanggal_masuk' => 'nullable',
                'barang_masuk' => 'required|numeric',
                'keterangan' => 'nullable',
                'satuan_jadi' => 'nullable',
                'satuan_mentah' => 'nullable',
            ]);

            $tipe_barang = $data['tipe_barang'];

            if ($tipe_barang == "barang_mentah") {
                $child = SatuanChild::find($data['satuan_mentah']);
            
                $barang_masuk = $data['barang_masuk'];

                $barang_masuk = $barang_masuk * $child->nilai;
                
                $data['barang_masuk'] = $barang_masuk;
            } elseif ($tipe_barang == "barang_jadi") {
                $child = SatuanJadiChild::find($data['satuan_jadi']);
                
                $barang_masuk = $data['barang_masuk'];
                
                $barang_masuk = $barang_masuk * $child->nilai;
                
                $data['barang_masuk'] = $barang_masuk;
            }
            unset($data['satuan_jadi']);
            unset($data['satuan_mentah']);

            
            StokMasuk::where('uuid', $uuid)->update($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }


    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            StokMasuk::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Stok Masuk berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
