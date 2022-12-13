<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\UserMenu;
use Exception;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public static function generateRoute()
    {
        try {
            if (Cache::has('menu') && getenv('APP_ENV') === 'production') {
                $menus = Cache::get('menu');
            } else {
                $menus = Menu::where('parent_id', 0)->get();
                Cache::put('menu', $menus, now()->addDays(30));
            }
            foreach ($menus as $menu) {
                $breadcrumb = [$menu->name];

                if ($menu->children->count() > 0 && !isset($menu->url)) {
                    self::generateRouteChildren($menu->children, $breadcrumb);
                } else {
                    Route::get($menu->url, function () use ($menu, $breadcrumb) {
                        return Inertia::render($menu->component, [
                            'title' => $menu->name,
                            'breadcrumb' => $breadcrumb,
                            'auth' => $menu['auth'],
                            'menu' => $menu
                        ]);
                    })->name($menu->route);

                    if ($menu->children->count() > 0) {
                        self::generateRouteChildren($menu->children, $breadcrumb);
                    }
                }
            }
        } catch (Exception $e) {
            echo '*************************************' . PHP_EOL;
            echo 'Error fetching database menus: ' . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            echo '*************************************' . PHP_EOL;
        }
    }

    public static function generateRouteChildren($childrens, $breadcrumb)
    {
        foreach ($childrens as $menu) {
            $newBreadcrumb = [...$breadcrumb];
            $newBreadcrumb[] = $menu->name;
            if ($menu->children->count() > 0 && !isset($menu->url)) {
                self::generateRouteChildren($menu->children, $newBreadcrumb);
            } else {
                Route::get($menu->url, function () use ($menu, $newBreadcrumb) {
                    return Inertia::render($menu->component, [
                        'title' => $menu->name,
                        'breadcrumb' => $newBreadcrumb,
                        'auth' => $menu['auth'],
                        'menu' => $menu
                    ]);
                })->name($menu->route);

                if ($menu->children->count() > 0) {
                    self::generateRouteChildren($menu->children, $newBreadcrumb);
                }
            }
        }
    }

    
     public function paginate(Request $request) {
         if (request()->wantsJson()) {
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);
        
            DB::statement(DB::raw('set @nomor=0+' . $page * $per));
            $courses = Menu::where(function ($q) use ($request) {
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
                        'name' => 'required|string',
                        'icon' => 'nullable',
                        'url' => 'nullable',
                        'route' => 'nullable',
                        'component' => 'nullable',
                        'parent_id' => 'nullable',
                        'shown' => 'nullable',
                        'auth' => 'nullable',
                        'level' => 'required',
                    ]);

                    if (isset($data['auth']) && $data['auth'] === 'on') {
                        $data['auth'] = 1;
                    } else {
                        $data['auth'] = 0;
                    }

                    if (isset($data['shown']) && $data['shown'] === 'on') {
                        $data['shown'] = 1;
                    } else {
                        $data['shown'] = 0;
                    }

                    

                    Menu::create($data);




        
                    return response()->json(['message' => 'Menu berhasil diperbarui']);
                } else {
                    return abort(404);
                }
            }
        
            public function get() {
                if (request()->wantsJson()) {
                    $data = Menu::all();
                    return response()->json($data);
                } else {
                    return abort(404);
                }
            }
        
            public function edit($uuid) {
                if (request()->wantsJson() && request()->ajax()) {
                    $data = Menu::where('uuid', $uuid)->first();
                    return response()->json($data);
                } else {
                    return abort(404);
                }
            }
        
            public function update(Request $request, $uuid) {
                if (request()->wantsJson() && request()->ajax()) {
                    $data = $request->validate([
                        'name' => 'required|string',
                        'icon' => 'nullable',
                        'url' => 'nullable',
                        'route' => 'nullable',
                        'component' => 'nullable',
                        'parent_id' => 'nullable',
                        'shown' => 'nullable',
                        'auth' => 'nullable',
                        'level' => 'required',
                    ]);

                    if (isset($data['auth']) && $data['auth'] === 'on') {
                        $data['auth'] = 1;
                    } else {
                        $data['auth'] = 0;
                    }

                    if (isset($data['shown']) && $data['shown'] === 'on') {
                        $data['shown'] = 1;
                    } else {
                        $data['shown'] = 0;
                    }
                    Menu::where('uuid', $uuid)->update($data);


        
                    return response()->json(['message' => 'Menu berhasil diperbarui']);
                } else {
                    return abort(404);
                }
            }
        
            public function destroy($uuid) {
                if (request()->wantsJson() && request()->ajax()) {
                    Menu::where('uuid', $uuid)->delete();
                    return response()->json(['message' => 'Menu berhasil dihapus']);
                } else {
                    return abort(404);
                }
            }
    
}
