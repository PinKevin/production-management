<?php

namespace App\Models;

use App\Models\PPIC\ProductionPlan;
use App\Models\Production\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function productionPlans(): HasMany
    {
        return $this->hasMany(ProductionPlan::class);
    }

    public function productionOrder(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }
}
