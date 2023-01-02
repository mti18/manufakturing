<?php

namespace App\Http\Controllers;

use App\Helpers\RestApi;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;


class AccountController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Account::where(function ($q) use ($request) {
                $q->where('nm_account', 'LIKE', '%' . $request->search . '%');
            })->paginate($per, ['*', DB::raw('@nomor  := @nomor  + 1 AS nomor')]);

            return response()->json($courses);
        } else {
            return abort(404);
        }
    }

    public function store(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_account' => 'required|string',
                'account_type'  => 'required|string',
                'type'  => 'required|string',
                'account_header'  => 'required|string',

            ]);

            $request->merge([
                'kode_account' => $this->getCode($request->parent_id)
            ]);
           
            $data = Account::create($request->all());



            return response()->json(['message' => ' Account berhasil ditambahkan']);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('parent')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function showPajak() {
        if (request()->wantsJson()) {
            $data = Account::where('account_header', 'pajak')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function showBank() {
        if (request()->wantsJson()) {
            $data = Account::where('account_header', 'bank', '1')->get();

            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function child() {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('nodes')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function show() {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('nodes')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }
    public function get1() {
        if (request()->wantsJson()) {
            $data = Account::where('parent_id', null)->with('nodes')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = Account::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_account' => 'required|string',
                'kode_account' => 'required|string',
                'account_type'  => 'required|string',
                'type'  => 'required|string',

            ]);
            $data = Account::findByUuid($uuid);


            //  Account::where('account_id', $data->id)->delete();
            // 
         
            $data->update($request->all());
     

            return response()->json(['message' => ' Account berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            Account::where('uuid', $uuid)->delete();
            return response()->json(['message' => ' Account berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
    public function getCode($parent = null)
    {
        if($parent == null){
            $data = Account::doesntHave('parent')->pluck('kode_account')->toArray();
            if(count($data) > 0){
                $data = array_map('intval', $data);
                sort($data);
                return end($data)+1;
            } else {
                return '1';
            }
        } else {
            $data = Account::where('parent_id', $parent)->pluck('kode_account')->toArray();
            $acc = Account::where('id', $parent)->first();
            if(count($data) > 0){
                $tampung = [];
                foreach($data as $item){
                    $exp = explode('.', $item);
                    array_push($tampung, intval(end($exp)));
                }
                sort($tampung);
                return $acc->kode_account.'.'.end($tampung)+1;
            } else {
                return $acc->kode_account.'.1';
            }
        }
    }
    public function getdata()
    {
        $data = Account::where('parent_id', null)->with('nodes')->get();
        return RestApi::success($data);
    }

    public function getHutang()
    {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('nodes')->where('account_header', 'hutang')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function getPiutang()
    {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('nodes')->where('account_header', 'piutang')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function getBank()
    {
        if (request()->wantsJson()) {
            $data = Account::doesntHave('nodes')->where('account_header', 'bank')->orderBy('kode_account')->get();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function send(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'nm_account' => 'required|string',
            ]);

            $request->merge([
                'kode_account' => $this->getCode($request->parent_id)
            ]);
           
            $data = Account::create($request->all());
            return response()->json(['message' => ' Account berhasil diperbarui']);
        } else {
            return abort(404);
        }
    }
}
