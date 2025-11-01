<?php

namespace App\Http\Resources\PPIC;

use App\Http\Resources\ProductResource;
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
            'productId' => $this->product_id,
            'createdAt' => $this->created_at,
            'product' => new ProductResource($this->whenLoaded('product'))
        ];
    }
}
