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

}
