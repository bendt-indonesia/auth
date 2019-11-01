<?php

namespace Bendt\Auth\Data\ModuleGroup;

use Bendt\Auth\Models\ModuleGroup;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleGroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (ModuleGroup $model) {
            return (new ModuleGroupResource($model))->additional($this->additional);
        });

        return parent::toArray($request);
    }
}
