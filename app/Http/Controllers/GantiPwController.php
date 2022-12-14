<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GantiPwController extends Controller
{
    public function updatepw(Request $request)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $data = $request->validate([
                'oldpassword' => 'required',
                'newpassword' => 'required|confirmed',
            ]);

            $password = User::find(auth()->user()->id)->password;
            if (password_verify($data['oldpassword'], $password)) {

                $password = bcrypt($data['newpassword']);
                
                unset($data['oldpassword']);
                unset($data['newpassword']);
                
                User::find(auth()->user()->id)->update(['password' => $password]);

            } else {
                return response()->json(['message' => 'Password tidak sama'], 400);
            }

            return response()->json(['message' => 'Password berhasil diganti']);
        } else {
            return abort(404);
        }
    }
}
