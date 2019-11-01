<?php

namespace Bendt\Auth\Data\RoleGroup;

use Bendt\Auth\Models\RoleGroup;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleGroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (RoleGroup $model) {
            return (new RoleGroupResource($model))->additional($this->additional);
        });

        return parent::toArray($request);
    }
}
