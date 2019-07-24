<?php

namespace Bendt\Auth\Seeder;

use Bendt\Auth\Models\Module;
use Bendt\Auth\Models\ModuleGroup;
use Bendt\Auth\Models\Role;
use Bendt\Auth\Models\RoleGroup;
use Bendt\Auth\Models\RoleGroupPivot;
use Illuminate\Support\Str;

class AuthSeeder
{

    protected $groups = [
        'system' => [
            'table_name' => 'Master Table',
        ],
    ];
    protected $roleGroups = [
        [
            'name' => 'Role Name Example',
            'description' => 'Full Access to all sources',
            'roles' => [
                'all',
                'group' => [                 //must be included first
                    'group_name',            //string, includes all sub child & visibility true
                    'group_name' => [
                        'table_name',        //if string, then all permission granted
                        'table_name' => ['create', 'view', 'update']          //if array allowed existed action
                    ]
                ],
                'table' => [
                    'table_name',    //if string, then all permission granted,
                    'table_name' => ['create', 'view', 'update']
                ]
            ]
        ],
    ];

    protected $customRoute = [];
    protected $roles = [];
    protected $groupedRolesByTable = [];
    protected $modules = [];
    protected $module_group = [];

    protected $actions = [
        'view',
        'store',
        'update',
        'destroy',
    ];

    public function __construct($groups, $roleGroups, $customRoute = [], $actions = [])
    {
        $this->groups = $groups;
        $this->roleGroups = $roleGroups;
        if (count($this->customRoute) > 0) $this->customRoute = $customRoute;
        if (count($actions) > 0) $this->actions = $actions;
    }

    public function seed()
    {
        $this->roles();
        $this->role_group();
    }

    public function roles()
    {
        foreach ($this->groups as $group => $tables) {
            $groupModel = new ModuleGroup([
                'name' => Str::title($group),
                'slug' => Str::slug($group)
            ]);
            $groupModel->save();

            foreach ($tables as $table => $title) {
                $module = new Module([
                    'name' => $title,
                    'group_id' => $groupModel->id,
                    'slug' => '/' . $groupModel->slug . '/' . Str::slug($table),
                ]);
                $module->save();

                foreach ($this->actions as $act) {
                    $role = new Role([
                        'name' => strtoupper($act . '_' . $table),
                        'type' => $act,
                        'module_id' => $module->id
                    ]);
                    $role->save();

                    $this->roles[] = [
                        'group' => $groupModel->slug,
                        'table' => $table,
                        'role' => $role
                    ];
                }

            }
        }

        foreach ($this->customRoute as $slug => $name) {
            $module = new Module([
                'name' => $name,
                'slug' => $slug,
                'group_id' => 1,
            ]);
            $module->save();

            $role = new Role([
                'name' => strtoupper('view_' . Str::snake($name)),
                'type' => 'view',
                'module_id' => $module->id
            ]);
            $role->save();
            $this->roles[] = [
                'group' => 'others',
                'table' => null,
                'role' => $role
            ];
        }
    }

    public function role_group()
    {
        $this->groupedRolesByTable = collect($this->roles)->groupBy('table')->all();

        foreach ($this->roleGroups as $rg) {
            $roleGroupRoles = $rg['roles'];
            unset($rg['roles']);
            $model = new RoleGroup($rg);
            $model->save();

            foreach ($roleGroupRoles as $type => $roles) {
                if (gettype($roles) === 'string' && $roles === 'all') {
                    $this->seed_role_group_pivot_all($model->id);
                } else if (gettype($roles) === 'array') {
                    //type of array
                    if ($type === 'group') {
                        $this->role_group_pivot_by_group($model->id, $roles, $rg);
                    } else if ($type === 'table') {
                        $this->role_group_pivot_by_table($model->id, $roles, $rg);
                    }
                }
            }
        }
    }

    public function seed_role_group_pivot_all($role_group_id)
    {
        foreach ($this->roles as $role) {
            $pivot = new RoleGroupPivot([
                'role_id' => $role['role']->id,
                'role_group_id' => $role_group_id,
                'is_visible' => 1,
            ]);
            $pivot->save();
        }
    }

    private function role_group_pivot_by_group($role_group_id, $groups, $role_group)
    {
        foreach ($groups as $group => $options) {
            if (gettype($options) === 'array') {
                foreach ($options as $table) {
                    if (gettype($table) === 'string') {
                        if (isset($groupedRolesByTable[$table])) {
                            foreach ($this->groupedRolesByTable[$table] as $role) {
                                $this->seed_role_group_pivot($role['role']->id, $role_group_id);
                            }
                        } else {
                            echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $group . '] -> table[' . $table . '] do not exists.' . PHP_EOL;
                        }
                    } else if (gettype($table) === 'array') {
                        foreach ($table as $tableName => $action) {
                            if (in_array($action, $this->actions)) {
                                foreach ($this->groupedRolesByTable[$table] as $role) {
                                    if ($this->groupedRolesByTable[$table]['role']->type == $action) {
                                        $this->seed_role_group_pivot($role['role']->id, $role_group_id);
                                    }
                                }
                            } else {
                                echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $group . '] -> table[' . $tableName . '] -> action[' . $action . '] incorrect' . PHP_EOL;
                            }
                        }
                    }
                }
            } else if (gettype($options) === 'string') {
                //Check the string is present or exists in group
                if (isset($this->groups[$group])) {
                    foreach ($this->roles as $role) {
                        if ($role['group'] === $group) {
                            $this->seed_role_group_pivot($role['role']->id, $role_group_id);
                        }
                    }
                } else {
                    echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $group . '] do not EXIST!' . PHP_EOL;
                }
            } else {
                echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[group] -> incorrect_format' . PHP_EOL;
            }
        }
    }

    private function role_group_pivot_by_table($role_group_id, $tables, $role_group)
    {
        $groupedRolesByTable = collect($this->roles)->groupBy('table')->all();

        foreach ($tables as $table) {
            if (gettype($table) === 'array') {
                foreach ($table as $tableName => $action) {
                    if (in_array($action, $this->actions)) {
                        foreach ($groupedRolesByTable[$table] as $role) {
                            if ($groupedRolesByTable[$table]['role']->type == $action) {
                                $this->seed_role_group_pivot($role['role']->id, $role_group_id);
                            }
                        }
                    } else {
                        echo 'WARNING roleGroup[' . $role_group['name'] . '] -> table[' . $tableName . '] -> action[' . $action . '] incorrect' . PHP_EOL;
                    }
                }
            } else if (gettype($table) === 'string') {
                //Check the string is present or exists in group
                if (isset($this->groupedRolesByTable[$table])) {
                    foreach ($this->groupedRolesByTable[$table] as $role) {
                        $this->seed_role_group_pivot($role['role']->id, $role_group_id);
                    }
                } else {
                    echo 'WARNING roleGroup[' . $role_group['name'] . '] -> table[' . $table . '] do not EXIST!' . PHP_EOL;
                }
            } else {
                echo 'WARNING roleGroup[' . $role_group['name'] . '] -> table -> incorrect_format' . PHP_EOL;
            }
        }
    }

    private function seed_role_group_pivot($role_id, $role_group_id, $visiblity = 1)
    {
        try {
            $pivot = new RoleGroupPivot([
                'role_id' => $role_id,
                'role_group_id' => $role_group_id,
                'is_visible' => $visiblity,
            ]);
            $pivot->save();
        } catch (\Exception $e) {
            //silence
        }
    }
}
