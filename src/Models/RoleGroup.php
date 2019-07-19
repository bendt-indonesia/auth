<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{

    protected $table = 'role_group';

    protected $with = [];

    public function users()
    {
        return $this->hasMany(Bendt\Auth\User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_group_pivot', 'role_group_id', 'role_id');
    }

    public function rolesMenu()
    {
        return $this->belongsToMany(Role::class, 'role_group_pivot', 'role_group_id', 'role_id')->where('is_visible',true);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function hasAnyRole($roles)
    {
        $user_roles = $this->roles;
        foreach ($user_roles as $role) {
            if (in_array($role->name, $roles)) return true;
        }
        return false;
    }

    public function hasAllRoles($roles)
    {
        $user_roles = array_pluck($this->roles, ['name']);
        foreach ($roles as $role) {
            if (!in_array($role, $user_roles)) return false;
        }
        return true;
    }

}
