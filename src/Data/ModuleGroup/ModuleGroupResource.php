<?php

namespace Bendt\Auth\Data\ModuleGroup;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleGroupResource extends JsonResource
{
    /**
     * Transform the resource model ModuleGroup into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'name' => $this->name,
			'slug' => $this->slug,
			'icon' => $this->icon,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
        ];
    }
}
