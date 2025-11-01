<?php

namespace App\Models\PPIC;

use App\Enum\PlanStatus;
use App\Models\Product;
use App\Models\Production\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductionPlan extends Model
{
    /** @use HasFactory<\Database\Factories\PPIC\ProductionPlanFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'notes',
        'status',
        'deadline'
    ];

    protected $casts = [
        'status' => PlanStatus::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionOrder(): HasOne
    {
        return $this->hasOne(ProductionOrder::class);
    }
}
