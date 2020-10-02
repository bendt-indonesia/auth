<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    protected $table = 'module';

    protected $files = [];

    public function attributes()
    {
        return $this->hasMany(ModuleAttribute::class);
    }

    public function group()
    {
        return $this->belongsTo(ModuleGroup::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->orderBy('sort_no')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('sort_no')->with('parent');
    }

}
