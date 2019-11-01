<?php

namespace Bendt\Auth\Data\Module;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource model Module into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'name' => $this->name,
			'group_id' => $this->group_id,
			'group' => $this->group,
			'slug' => $this->slug,
			'is_active' => $this->is_active,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
        ];
    }
}
