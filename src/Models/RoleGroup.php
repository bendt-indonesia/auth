<?php

namespace Bendt\auth\Models;

use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{
    protected $table = 'role_group';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function hasAnyRole($roles)
    {
        $user_roles = $this->roles;
        foreach ($user_roles as $role)
        {
            if(in_array($role->name, $roles)) return true;
        }

        return false;
    }

    public function hasAllRoles($roles)
    {
        $user_roles = array_pluck($this->roles, ['name']);

        foreach ($roles as $role)
        {
            if(!in_array($role, $user_roles)) return false;
        }

        return true;
    }

}
