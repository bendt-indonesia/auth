<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public static $ADMIN_ROLE = 'admin';
    public static $MEMBER_ROLE = 'member';
    public static $SUPERADMIN_ROLE = 'superadmin';
    //
    public function groups()
    {
        return $this->belongsToMany(RoleGroup::class);
    }

}
