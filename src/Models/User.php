<?php

namespace Bendt\auth\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_root',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role_group()
    {
        return $this->belongsTo(RoleGroup::class);
    }

    public function getRoleList()
    {
        $roles = $this->getRoles();
        $list = [];
        foreach($roles as $role)
        {
            array_push($list, $role->name);
        }

        return $list;
    }

    public function getRoles()
    {
        if(!is_null($this->role_group))
        {
            return $this->role_group->getRoles();
        }
        else {
            return [];
        }
    }

    public function hasAnyRole($roles)
    {
        if(!is_null($this->role_group))
        {
            return $this->role_group->hasAnyRole($roles);
        }

        return false;
    }

    public function hasAllRoles($roles)
    {
        if(!is_null($this->role_group))
        {
            return $this->role_group->hasAllRoles($roles);
        }

        return false;
    }

    public function isRoot() {
        return $this->is_root;
    }
}
