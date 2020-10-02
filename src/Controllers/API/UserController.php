<?php

namespace Bendt\Auth\Controllers\API;

use Bendt\Auth\Controllers\ApiController;
use Bendt\Auth\Models\Module;
use Bendt\Auth\Models\ModuleGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserController extends ApiController
{

    /**
     * Display a listing of the resource User
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $roles = [];
            foreach ($user->role_group->roles as $row) {
                $roles[] = $row->name;
            }

            $moduleIds = [];
            foreach ($user->role_group->rolesMenu as $row) {
                if($row->type === 'view' && $row->module_id != '') {
                    $moduleIds[] = $row->module_id;
                }
            }

            $user->roles = $roles;
            $user->navigation = $this->getNavigation($user->id, $moduleIds);

            $user->role = $user->role_group->name;
            unset($user->role_group->rolesMenu);
            unset($user->role_group->roles);

            return $user;
        } catch (\Exception $e) {
            return $this->sendException($e);
        }
    }

    public function getNavigation($userId, $moduleIds) {
        $cacheKeys = config('bendt-auth.cache_keys', 'MfrD3rnHmV5PQxXY');
        $userCacheKeys = $userId.'--'.$cacheKeys;

        $value = Cache::get($userCacheKeys);
        if($value) {
            return $value;
        }

        $navigation = $this->fetchNavigation($moduleIds);
        Cache::put($userCacheKeys, $navigation);

        return $navigation;
    }

    public function generateMenu($menu) {
        $menu = [
            'id' => Str::uuid(),
            'type' => 'item',
            'title' => $menu->name,
            'icon' => $menu->icon ? $menu->icon : 'keyboard_arrow_right',
            'url' => $menu->slug,
        ];

        if(isset($menu->children) && count($menu->children) > 0) {
            $menu['type'] = 'collapse';
            $menu['icon'] = 'view_quilt';
            $menu['children'] = [];
            foreach ($menu->children as $child) {
                $menu['children'][] = $this->generateMenu($child);
            }
        }

        return $menu;
    }

    public function fetchNavigation($moduleIds) {
        $modules = Module::with(['group','children'])->orderBy('sort_no')->whereIn('id',$moduleIds)->get();
        $modules = collect($modules)->sortBy('group.sort_no')->groupBy('group_id')->map(function($item, $group_id) {
            $group = $item->first()->group;

            $return = [
                'id' => Str::uuid(),
                'group_id' => $group_id,
                'title' => $group->name,
                'name' => $group->name,
                'slug' => $group->slug,
                'type' => 'group',
                'children' => $item->map(function($child) {
                    return $this->generateMenu($child);
                }),
            ];

            return $return;
        });

        $navigation = [];
        foreach ($modules as $module) {
            $navigation[] = $module;
        }

        return $navigation;
    }
}
