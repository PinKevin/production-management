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
    public function updating(ProductionOrder $productionOrder): void
    {
        if ($productionOrder->isDirty('status')) {
            $originalStatus = $productionOrder->getOriginal('status')->value;
            $newStatus = $productionOrder->status->value;
            $notes = $productionOrder->logNotes;

            if ($productionOrder->status === OrderStatus::COMPLETED) {
                $notes = "Hasil jadi: $productionOrder->quantity_actual, hasil reject: $productionOrder->quantity_rejected";

                if (!empty($productionOrder->logNotes)) {
                    $notes .= " Tambahan: " . $productionOrder->logNotes;
                }
            } else if ($productionOrder->status === OrderStatus::CANCELED) {
                $notes = "Order dibatalkan";

                if (!empty($productionOrder->logNotes)) {
                    $notes .= " Alasan: " . $productionOrder->logNotes;
                }
            }

            if (empty($notes)) {
                $notes = "Status diubah dari $originalStatus menjadi $newStatus.";
            }

            ProductionLog::create([
                'user_id' => Auth::id(),
                'production_order_id' => $productionOrder->id,
                'status_from' => $originalStatus,
                'status_to' => $newStatus,
                'notes' => $notes,
            ]);

            unset($productionOrder->logNotes);
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
