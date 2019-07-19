<?php

namespace Bendt\Auth\Controllers\API;

use Bendt\Auth\Controllers\ApiController;
use Bendt\Auth\Models\Module;
use Bendt\Auth\Models\ModuleGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            $modules = Module::whereIn('id',$moduleIds)->get();
            $modules = collect($modules)->groupBy('group_id')->map(function($item, $group_id) {
                $group = $item->first()->group;

                $return = [
                    'id' => Str::uuid(),
                    'group_id' => $group_id,
                    'title' => $group->name,
                    'name' => $group->name,
                    'slug' => $group->slug,
                    'type' => 'group',
                    'children' => $item->map(function($child) {
                        $children = [
                            'id' => Str::uuid(),
                            'type' => 'item',
                            'title' => $child->name,
                            'icon' => $child->icon ? $child->icon : '',
                            'url' => $child->slug,
                        ];
                        return $children;
                    }),
                ];

                return $return;
            });

            $navigation = [];
            foreach ($modules as $module) {
                $navigation[] = $module;
            }

            $user->roles = $roles;
            $user->navigation = $navigation;

            $user->role = $user->role_group->name;
            unset($user->role_group);

            return $user;
        } catch (\Exception $e) {
            return $this->sendException($e);
        }
    }
}