<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'role';

    public function groups()
    {
        return $this->belongsToMany(RoleGroup::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function pivots()
    {
        return $this->hasMany(RoleGroupPivot::class);
    }

}
