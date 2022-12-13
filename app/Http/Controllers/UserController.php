<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Position;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function paginate(Request $request) {
        if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = User::where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
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
                'email' => 'required|unique:users,email',
                'nip' => 'required|unique:users,nip',
                'whatsapp' => 'required|unique:users,whatsapp',
                'password' => 'required',
                'user_group_uuid' => 'required|exists:user_groups,uuid',
                'position_uuid' => 'required|exists:positions,uuid',
                'address' => 'required',
                'birth_date' => 'required',
                'avatar' => 'required|image',
                'level' => 'required',
                'status' => 'required',
            ]);

            $data['password'] = bcrypt($data['password']);
            $data['avatar'] = 'storage/' . $request->avatar->store('user', 'public');

            $data['user_group_id'] = UserGroup::where('uuid', $data['user_group_uuid'])->first()->id;
            unset($data['user_group_uuid']);

            $data['position_id'] = Position::where('uuid', $data['position_uuid'])->first()->id;
            unset($data['position_uuid']);
            $data['is_active'] = 1;

            $user = User::create($data);

            return response()->json([
                'message' => 'User baru berhasil ditambahkan',
            ]);
        } else {
            return abort(404);
        }
    }

    public function get() {
        if (request()->wantsJson()) {
            $data = User::all();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function edit($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $data = User::where('uuid', $uuid)->first();
            return response()->json($data);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $user = User::where('uuid', $uuid)->first();

            $data = $request->validate([
                'name' => 'required',
                'email' => ['required', Rule::unique('users', 'email')->ignore($user->id)],
                'nip' => ['required', Rule::unique('users', 'nip')->ignore($user->id)],
                'whatsapp' => ['required', Rule::unique('users', 'whatsapp')->ignore($user->id)],
                'password' => 'nullable',
                'user_group_uuid' => 'required|exists:user_groups,uuid',
                'position_uuid' => 'required|exists:positions,uuid',
                'address' => 'required',
                'birth_date' => 'required',
                'avatar' => 'required|image',
                'level' => 'required',
                'status' => 'required',
            ]);
            $data['password'] = bcrypt($data['password']);
            $data['avatar'] = 'storage/' . $request->avatar->store('user', 'public');

            $data['user_group_id'] = UserGroup::where('uuid', $data['user_group_uuid'])->first()->id;
            unset($data['user_group_uuid']);

            $data['position_id'] = Position::where('uuid', $data['position_uuid'])->first()->id;
            unset($data['position_uuid']);

            $user->update($data);

            return response()->json([
                'message' => $user->name . ' berhasil diperbarui',
            ]);
        } else {
            return abort(404);
        }
    }

    public function destroy($uuid) {
        if (request()->wantsJson() && request()->ajax()) {
            $user = User::where('uuid', $uuid);
            $user->delete();
            return response()->json(['message' => $user->name . ' berhasil dihapus']);
        } else {
            return abort(404);
        }
    }
}
