<?php

namespace App\Models\PPIC;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlan extends Model
{
    /** @use HasFactory<\Database\Factories\PPIC\ProductionPlanFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
