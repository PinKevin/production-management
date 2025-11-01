<?php

namespace App\Http\Resources\Production;

use App\Http\Resources\ProductResource;
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
            'deadline' => $this->deadline,
            'createdAt' => $this->created_at,
            'product' => new ProductResource($this->whenLoaded('product')),
            'productionLogs' => ProductionLogResource::collection($this->whenLoaded('productionLogs'))
        ];
    }
}
