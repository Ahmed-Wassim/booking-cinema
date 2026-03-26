<?php

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HallSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'hall_id'     => $this->hall_id,
            'name'        => $this->name,
            'layout_type' => $this->layout_type,
            'base_config' => $this->base_config,
            'sort_order'  => $this->sort_order,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
