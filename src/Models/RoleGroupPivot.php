<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class RoleGroupPivot extends Model
{

    protected $table = 'role_group_pivot';

    public $timestamps = false;

    const FILE_PATH = "/role_group_pivot/";

    protected $guarded = [];

    protected $files = [];

    public function role_group()
    {
        return $this->belongsTo(RoleGroup::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
