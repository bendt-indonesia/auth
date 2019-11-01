<?php

namespace Bendt\Auth\Data\Module;

use Bendt\Auth\Models\Module;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Module $model) {
            return (new ModuleResource($model))->additional($this->additional);
        });

        return parent::toArray($request);
    }
}
