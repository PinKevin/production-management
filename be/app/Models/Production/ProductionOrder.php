<?php

namespace App\Models\Production;

use App\Enum\OrderStatus;
use App\Models\PPIC\ProductionPlan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends Model
{
    protected $fillable = [
        'product_id',
        'production_plan_id',
        'quantity_planned',
        'quantity_actual',
        'quantity_rejected',
        'status',
        'deadline'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class);
    }

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class);
    }
}
