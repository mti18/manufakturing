<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\UserMenu;
use App\Models\UserGroup;

class DashboardController extends Controller
{
    public function menu()
    {
        if (request()->wantsJson()) {
            $menus = Menu::with(['children' => function ($q) {
                $q->where('shown', true);
                $q->whereRelation('user_groups', 'user_groups.id', '=', auth()->user()->user_group_id);
            }])->where('parent_id', 0)->where(function ($q) {
                $q->where('level', 'LIKE', '%' . request()->user()->level . '%');
            })->where('shown', 1)->whereRelation('user_groups', 'user_groups.id', '=', auth()->user()->user_group_id)->orderBy('id')->get();

            return response()->json($menus);
        } else {
            return abort(404);
        }
    }

    public function menuAccess()
    {
        if (request()->wantsJson() && request()->ajax()) {
            $menus = $menus = Menu::with(['children' => function ($q) {
                $q->where('shown', true);
            }])->where('parent_id', 0)->where(function ($q) {
                $q->where('level', 'LIKE', '%' . request()->user()->level . '%');
            })->get();

            return response()->json($menus);
        } else {
            return abort(404);
        }
    }

    public function menuAccessSave(Request $request)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $user_group = UserGroup::where('uuid', $request->user_group_uuid)->first();
            UserMenu::where('user_group_id', $user_group->id)->delete();
            $menus = Menu::all();

            $new_menus = collect($request->menus)->unique();
            foreach ($new_menus as $menu) {
                $menu = $menus->where('uuid', $menu)->first();
                UserMenu::create([
                    'user_group_id' => $user_group->id,
                    'menu_id' => $menu->id
                ]);
            }

            return response()->json(['message' => 'Akses menu berhasil diperbarui.']);
        } else {
            return abort(404);
        }
    }

    public function menuAccessChecked(Request $request)
    {
        if (request()->wantsJson() && request()->ajax()) {
            $menus = Menu::whereHas('user_groups', function ($q) use ($request) {
                $q->where('user_groups.uuid', $request->user_group_uuid);
            })->get()->pluck('uuid');

            return response()->json($menus);
        } else {
            return abort(404);
        }
    }

    public function hasAccess(Request $request) {
        if (request()->wantsJson() && request()->ajax()) {
            $menu = Menu::where('uuid', $request->menu_uuid)->first();
            $check = UserMenu::where('menu_id', $menu->id)->where('user_group_id', auth()->user()->user_group_id)->first();

            if (isset($check->id)) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false], 403);
            }
        } else {
            return abort(404);
        }
    }
}
