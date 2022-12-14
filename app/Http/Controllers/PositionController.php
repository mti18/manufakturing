<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Position;

class PositionController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Position::where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('code', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'name' => 'required',
                'code' => 'required',
            ]);
            Position::create($data);

            return response()->json(['message' => 'Jabatan berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Position::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Position::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
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
            Position::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Jabatan berhasil dihapus']);
        } else {
            return abort(404);
        }
    }

    public function getcode()
    {
        $data = Position::pluck('code')->toArray();
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
                    return ($start);
                }
                $start++;
            }
            return ($start);

        }
    }

}
