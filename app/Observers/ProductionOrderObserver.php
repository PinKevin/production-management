<?php

namespace App\Observers;

use App\Enum\OrderStatus;
use App\Models\Production\ProductionLog;
use App\Models\Production\ProductionOrder;
use Illuminate\Support\Facades\Auth;

class ProductionOrderObserver
{
    /**
     * Handle the ProductionOrder "created" event.
     */
    public function created(ProductionOrder $productionOrder): void
    {
        ProductionLog::create([
            'user_id' => Auth::id(),
            'production_order_id' => $productionOrder->id,
            'status_from' => null,
            'status_to' => $productionOrder->status->value,
            'notes' => 'Order dibuat'
        ]);
    }

    /**
     * Handle the ProductionOrder "updated" event.
     */
    public function updated(ProductionOrder $productionOrder): void
    {
        if ($productionOrder->wasChanged('status')) {
            $originalStatus = $productionOrder->getOriginal('status')->value;
            $newStatus = $productionOrder->status->value;

            $notes = "Status diubah dari $originalStatus menjadi $newStatus.";
            if ($productionOrder->status === OrderStatus::COMPLETED) {
                $notes = "Hasil jadi: $productionOrder->quantity_actual,
                        hasil reject: $productionOrder->quantity_rejected";
            }

            ProductionLog::create([
                'user_id' => Auth::id(),
                'production_order_id' => $productionOrder->id,
                'status_from' => $originalStatus,
                'status_to' => $newStatus,
                'notes' => $notes,
            ]);
        }
    }

    /**
     * Handle the ProductionOrder "deleted" event.
     */
    public function deleted(ProductionOrder $productionOrder): void
    {
        //
    }

    /**
     * Handle the ProductionOrder "restored" event.
     */
    public function restored(ProductionOrder $productionOrder): void
    {
        //
    }

    /**
     * Handle the ProductionOrder "force deleted" event.
     */
    public function forceDeleted(ProductionOrder $productionOrder): void
    {
        //
    }
}
