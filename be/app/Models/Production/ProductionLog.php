<?php

namespace App\Models\Production;

use App\Enum\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'production_order_id',
        'status_from',
        'status_to',
        'notes',
        'created_at'
    ];

    protected $casts = [
        'status_from' => OrderStatus::class,
        'status_to' => OrderStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }
}
