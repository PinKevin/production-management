<?php

namespace App\Http\Resources\Production;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionOrderResource extends JsonResource
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
            'product_id' => $this->product_id,
            'quantity_planned' => $this->quantity_planned,
            'quantity_actual' => $this->quantity_actual,
            'quantity_rejected' => $this->quantity_rejected,
            'status' => $this->status,
            'deadline' => $this->deadline
        ];
    }
}
