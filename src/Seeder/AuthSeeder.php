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
    protected $groupNameAliases = [];

    protected $groups = [
        'system' => [                   //should be in lower case
            'table_name' => 'Master Table',
            'custom' => [                  //Level 2 Route
                'title' => 'Master Table',
                'actions' => ['view', 'update']  // Receive Array or string 'all' || if unset then all actions
            ],
        ],
    ];
    protected $roleGroups = [
        [
            'name' => 'Role Name Example',
            'description' => 'Full Access to all sources',
            'roles' => [
                'all',
                'group' => [                 //must be included first
                    'group_name',            //string, lowercase, includes all sub child & visibility true
                ],
                'table' => [
                    'sort_no' => 1, //optional
                    'table_name',    //if string, then all permission granted,
                    'table_name' => ['create', 'view', 'update']
                ]
            ]
        ],
    ];

    protected $customRoute = [
        'others' => [                               //Level 1 always be group / department
            'role-management' => [                  //Level 2 Route
                'title' => 'Hak Akses',
                'actions' => ['view', 'update']
            ],
            'test-route' => 'Testing Page'          // check if string then all actions created
        ]
    ];
    protected $roles = [];
    protected $groupedRolesByTable = [];
    protected $modules = [];
    protected $module_group = [];
    protected $hidden = [];

    protected $actions = [
        'view',
        'store',
        'update',
        'destroy',
    ];

    public function __construct($groups, $roleGroups, $customRoute = [], $hidden = [], $actions = [], $groupNameAliases = [])
    {
        $this->groups = $groups;
        $this->roleGroups = $roleGroups;
        $this->customRoute = $customRoute;
        $this->hidden = $hidden;
        $this->groupNameAliases = $groupNameAliases;
        if (count($actions) > 0) $this->actions = $actions;
    }

    public function seed()
    {
        $this->roles();
        $this->role_group();
    }

    public function update_seed()
    {
        $this->sync_roles();
    }

    public function getModuleGroups()
    {
        $groups = ModuleGroup::all();
        return $groups;
    }

    public function getModules()
    {
        $groups = Module::all();
        return $groups;
    }

    public function storeModuleGroup($group, $sort_no = 1)
    {
        $group_slug = Str::slug($group);
        $groupModel = new ModuleGroup([
            'name' => isset($this->groupNameAliases[$group]) ? Str::title($this->groupNameAliases[$group]) : Str::title($group),
            'slug' => $group_slug,
            'sort_no' => $sort_no,
        ]);
        $groupModel->save();

        return $groupModel;
    }

    public function storeModule($data)
    {
        $module = new Module($data);
        $module->save();

        return $module;
    }

    public function findModule($modules, $moduleGroupModel, $actions, $table_name, $name, $sort_no)
    {
        $table_slug = Str::slug($table_name);
        $length = strlen($table_slug) * -1;
        echo $table_slug . PHP_EOL;
        foreach ($modules as $module) {
            if ($table_slug === 'language-option') {
                echo $module->id . ' - ' . substr($module->slug, $length) . ' === ' . $table_slug . PHP_EOL;
            }
            if (substr($module->slug, $length) === $table_slug) {
                // $module found and then group update
                $module->name = $name;
                $module->sort_no = $sort_no;
                $module->is_visible = in_array($table_name, $this->hidden) ? 0 : 1;
                $module->group_id = $moduleGroupModel->id;
                $module->slug = '/' . $moduleGroupModel->slug . '/' . $table_slug;
                $module->save();
                $this->update_role($actions, $module, $moduleGroupModel->slug, $table_name);
                return $module;
            }
        }

        $module = $this->storeModule([
            'name' => $name,
            'group_id' => $moduleGroupModel->id,
            'slug' => '/' . $moduleGroupModel->slug . '/' . $table_slug,
            'table' => $table_name,
            'sort_no' => $sort_no,
            'is_visible' => in_array($table_name, $this->hidden) ? 0 : 1
        ]);
        $this->create_role($actions, $module, $moduleGroupModel->slug, $table_name);

        return false;
    }

    public function sync_roles()
    {
        $existing_group_key = [];
        $modGroup = [];
        $moduleGroups = $this->getModuleGroups();
        $modules = $this->getModules();

        foreach ($moduleGroups as $row) {
            $modGroup[] = $row;
            $existing_group_key[] = $row->slug;
        }

        $group_sort_no = 1;
        foreach ($this->groups as $group => $tables) {
            $group = Str::snake($group);
            $group_slug = Str::slug($group);

            if (!in_array($group_slug, $existing_group_key)) {
                $moduleGroupModel = $this->storeModuleGroup($group, $group_sort_no++);
                $modGroup[] = $moduleGroupModel;
            } else {
                $moduleGroupModel = collect($moduleGroups)->where('slug', $group_slug)->first();
            }

            foreach ($tables as $table => $content) {
                if (is_array($content)) {
                    $sort_no = isset($content['sort_no']) ? $content['sort_no'] : 0;
                    $actions = isset($content['actions']) ? $content['actions'] : $this->actions;
                    $this->findModule($modules, $moduleGroupModel, $actions, $table, $content['title'], $sort_no);
                } else {
                    $this->findModule($modules, $moduleGroupModel, $this->actions, $table, $content, 0);
                }
            }
        }

        foreach ($this->customRoute as $group_name => $routes) {
            $group = Str::snake($group_name);

            if (!isset($modGroup[$group])) {
                echo 'Custom Route > Group ' . $group_name . ' not exists, routes skiped.' . PHP_EOL;
                continue;
            }
            $moduleGroupModel = $modGroup[$group];

            foreach ($routes as $table => $content) {
                if (is_array($content)) {
                    $sort_no = isset($content['sort_no']) ? $content['sort_no'] : 0;
                    $actions = isset($content['actions']) ? $content['actions'] : $this->actions;
                    $this->findModule($modules, $moduleGroupModel, $actions, $table, $content['title'], $sort_no);
                } else {
                    $this->findModule($modules, $moduleGroupModel, $this->actions, $table, $content, 0);
                }
            }
        }
    }


    public function roles()
    {
        $modGroup = [];

        $group_sort_no = 1;
        foreach ($this->groups as $group => $tables) {
            $group = Str::snake($group);
            $roleGroup = $this->storeModuleGroup($group, $group_sort_no++);
            $modGroup[] = $roleGroup;
            foreach ($tables as $table => $content) {
                $table_slug = Str::slug($table);
                if (is_array($content)) {
                    $module = $this->storeModule([
                        'name' => $content['title'],
                        'group_id' => $roleGroup->id,
                        'slug' => '/' . $roleGroup->slug . '/' . $table_slug,
                        'table' => $table,
                        'sort_no' => isset($content['sort_no']) ? $content['sort_no'] : 0,
                        'is_visible' => in_array($table, $this->hidden) ? 0 : 1
                    ]);
                    $actions = isset($content['actions']) ? $content['actions'] : $this->actions;
                    $this->create_role($actions, $module, $roleGroup->slug, $table);
                } else {
                    $module = $this->storeModule([
                        'name' => $content,
                        'group_id' => $roleGroup->id,
                        'slug' => '/' . $roleGroup->slug . '/' . $table_slug,
                        'table' => $table,
                        'is_visible' => in_array($table, $this->hidden) ? 0 : 1
                    ]);
                    $this->create_role($this->actions, $module, $roleGroup->slug, $table);
                }
            }
        }

        $modGroup = collect($modGroup)->keyBy('slug');
        foreach ($this->customRoute as $group => $routes) {
            $group = Str::snake($group);

            if (!isset($modGroup[$group])) continue;
            $group = $modGroup[$group];
            foreach ($routes as $table => $content) {
                if (is_array($content)) {
                    $module = $this->storeModule([
                        'name' => $content['title'],
                        'group_id' => $group->id,
                        'slug' => '/' . Str::slug($table),
                        'sort_no' => isset($content['sort_no']) ? $content['sort_no'] : 0,
                    ]);
                    $actions = isset($content['actions']) ? $content['actions'] : $this->actions;
                    $this->create_role($actions, $module, $group->slug, $table);
                } else {
                    $module = $this->storeModule([
                        'name' => $content,
                        'group_id' => $group->id,
                        'slug' => '/' . Str::slug($table),
                    ]);
                    $this->create_role($this->actions, $module, $group->slug, $table);

                }
            }
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
                'is_visible' => in_array($role['table'], $this->hidden) ? 0 : 1,
            ]);
            $pivot->save();
        }
    }

    private function create_module()
    {

    }

    private function create_role($actions, $module, $group, $table)
    {
        if ($actions === 'all') {
            $actions = $this->actions;
        }

        foreach ($actions as $act) {
            $role = new Role([
                'name' => strtoupper($act . '_' . Str::snake($table)),
                'type' => $act,
                'module_id' => $module->id
            ]);
            $role->save();

            $this->roles[] = [
                'group' => $group,
                'table' => $table,
                'role' => $role
            ];
        }
    }

    private function update_role($actions, $module, $group, $table)
    {
        if ($actions === 'all') {
            $actions = $this->actions;

            $existing_actions = [];

            foreach ($module->roles as $role) {
                $existing_actions[] = $role->type;
                $this->roles[] = [
                    'group' => $group,
                    'table' => $table,
                    'role' => $role
                ];
            }

            $actions = array_diff($actions, $existing_actions);

            foreach ($actions as $act) {
                $role = new Role([
                    'name' => strtoupper($act . '_' . Str::snake($table)),
                    'type' => $act,
                    'module_id' => $module->id
                ]);
                $role->save();

                $this->roles[] = [
                    'group' => $group,
                    'table' => $table,
                    'role' => $role
                ];
            }
        } else {
            foreach ($module->roles as $role) {
                if (!in_array($role->type, $actions)) {
                    $role->delete();
                } else {
                    $actions = array_diff($actions, [$role->type]);
                    $this->roles[] = [
                        'group' => $group,
                        'table' => $table,
                        'role' => $role
                    ];
                }
            }

            foreach ($actions as $act) {
                $role = new Role([
                    'name' => strtoupper($act . '_' . Str::snake($table)),
                    'type' => $act,
                    'module_id' => $module->id
                ]);
                $role->save();

                $this->roles[] = [
                    'group' => $group,
                    'table' => $table,
                    'role' => $role
                ];
            }
        }
    }

    private function role_group_pivot_by_group($role_group_id, $groups, $role_group)
    {
        foreach ($groups as $group => $options) {
            /*
             * if (gettype($options) === 'array') {
                foreach ($options as $table) {
                    if (gettype($table) === 'string') {
                        if (isset($groupedRolesByTable[$table])) {
                            foreach ($this->groupedRolesByTable[$table] as $role) {
                                $hidden = in_array($role['table'],$this->hidden) ? 0 : 1;
                                $this->seed_role_group_pivot($role['role']->id, $role_group_id, $hidden);
                            }
                        } else {
                            echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $group . '] -> table[' . $table . '] do not exists.' . PHP_EOL;
                        }
                    } else if (gettype($table) === 'array') {
                        foreach ($table as $tableName => $action) {
                            if (in_array($action, $this->actions)) {
                                foreach ($this->groupedRolesByTable[$table] as $role) {
                                    if ($this->groupedRolesByTable[$table]['role']->type == $action) {
                                        $hidden = in_array($role['table'],$this->hidden) ? 0 : 1;
                                        $this->seed_role_group_pivot($role['role']->id, $role_group_id, $hidden);
                                    }
                                }
                            } else {
                                echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $group . '] -> table[' . $tableName . '] -> action[' . $action . '] incorrect' . PHP_EOL;
                            }
                        }
                    }
                }
            } else
            */

            if (gettype($options) === 'string') {
                //Check the string is present or exists in group
                if (isset($this->groups[$options])) {
                    $options = strtolower($options);
                    foreach ($this->roles as $role) {
                        if ($role['group'] === $options) {
                            $hidden = in_array($role['table'], $this->hidden) ? 0 : 1;
                            $this->seed_role_group_pivot($role['role']->id, $role_group_id, $hidden);
                        }
                    }
                } else {
                    echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[' . $options . '] do not EXIST!' . PHP_EOL;
                }
            } else {
                echo 'WARNING roleGroup[' . $role_group['name'] . '] -> roles[group] -> incorrect_format' . PHP_EOL;
            }
        }
    }

    private function role_group_pivot_by_table($role_group_id, $tables, $role_group)
    {
        $groupedRolesByTable = collect($this->roles)->groupBy('table')->all();

        foreach ($tables as $tableArrayName => $table) {
            if (gettype($table) === 'array') {
                foreach ($table as $action) {
                    if (in_array($action, $this->actions)) {
                        foreach ($groupedRolesByTable[$tableArrayName] as $role) {
                            if ($role['role']->type == $action) {
                                $hidden = in_array($role['table'], $this->hidden) ? 0 : 1;
                                $this->seed_role_group_pivot($role['role']->id, $role_group_id, $hidden);
                            }
                        }
                    } else {
                        echo 'WARNING roleGroup[' . $role_group['name'] . '] -> table[' . $table . '] -> action[' . $action . '] incorrect' . PHP_EOL;
                    }
                }
            } else if (gettype($table) === 'string') {
                //Check the string is present or exists in group
                if (isset($this->groupedRolesByTable[$table])) {

                    foreach ($this->groupedRolesByTable[$table] as $role) {
                        $hidden = in_array($role['table'], $this->hidden) ? 0 : 1;
                        $this->seed_role_group_pivot($role['role']->id, $role_group_id, $hidden);
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
