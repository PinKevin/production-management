<?php

namespace App\Http\Resources\PPIC;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionPlanResource extends JsonResource
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
            'status' => $this->status,
            'deadline' => $this->deadline,
            'quantity' => $this->quantity,
            'notes' => $this->notes,
            'product_id' => $this->product_id
        ];
    }
}
