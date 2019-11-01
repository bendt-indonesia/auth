<?php

namespace Bendt\Auth\Data\RoleGroup;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleGroupResource extends JsonResource
{
    /**
     * Transform the resource model RoleGroup into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
        ];
    }
}
