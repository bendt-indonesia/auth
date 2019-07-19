<?php

namespace Bendt\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAttribute extends Model
{

    protected $table = 'module_attribute';

    protected $files = [];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

}
