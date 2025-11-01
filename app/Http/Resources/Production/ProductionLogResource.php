<?php

namespace App\Http\Resources\Production;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'statusFrom' => $this->status_from,
            'statusTo' => $this->status_to,
            'user' => new UserResource($this->whenLoaded('user')),
            'notes' => $this->notes,
            'createdAt' => $this->created_at
        ];
    }
}
