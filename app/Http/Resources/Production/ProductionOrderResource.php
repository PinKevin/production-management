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
            'productId' => $this->product_id,
            'quantityPlanned' => $this->quantity_planned,
            'quantityActual' => $this->quantity_actual,
            'quantityRejected' => $this->quantity_rejected,
            'status' => $this->status,
            'deadline' => $this->deadline
        ];
    }
}
