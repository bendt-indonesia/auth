<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleGroup extends Model
{

    protected $table = 'module_group';

    protected $files = [];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

}
