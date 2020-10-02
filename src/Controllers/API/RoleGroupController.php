<?php

namespace Bendt\Auth\Controllers\API;

use Bendt\Auth\Controllers\ApiController;
use Bendt\Auth\Models\Module;
use Bendt\Auth\Models\ModuleGroup;
use Bendt\Auth\Models\Role;
use Bendt\Auth\Models\RoleGroup;
use Bendt\Auth\Models\RoleGroupPivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleGroupController extends ApiController
{
    /**
     * Display all roles group along with module and roles
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function roles(Request $request)
    {
        try {
            $this->authorize('view', Auth::user(), RoleGroup::class);

            $groups = ModuleGroup::all();
            $modules = Module::all();

            return $this->sendResponse([
                'data' => $modules,
                'groups' => $groups
            ]);
        } catch (\Exception $e) {
            return $this->sendException($e);
        }
    }

    /**
     * Bulk Remove the specified ids RoleGroup resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveRoles(Request $request)
    {
        try {
            // $this->authorize('update', $this->model);

            $this->validate($request, [
                'ids' => 'required|array',
                'role_group_id' => 'required|exists:role_group,id'
            ]);

            $ids = $request->input('ids');
            $role_group_id = $request->input('role_group_id');

            DB::beginTransaction();

            RoleGroupPivot::where('role_group_id',$role_group_id)->delete();

            $hiddenModule = Module::where('is_visible',false)->pluck('id');
            $hiddenRole = Role::whereIn('module_id',$hiddenModule)->pluck('id')->toArray();

            foreach ($ids as $id) {
                $pivot = new RoleGroupPivot([
                    'role_group_id' => $role_group_id,
                    'role_id' => $id,
                    'is_visible' => in_array($id,$hiddenRole) ? false : true,
                ]);
                $pivot->save();
            }

            Cache::flush();

            DB::commit();

            return $this->sendResponse([], null, 204);
        } catch (\Exception $e) {
            if(method_exists($e,'errors')) {
                return $this->sendValidationError($e->errors());
            }
            return $this->sendException($e);
        }
    }

}
