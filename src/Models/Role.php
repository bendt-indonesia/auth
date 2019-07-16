<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function groups()
    {
        return $this->belongsToMany(RoleGroup::class);
    }

}
