<?php

namespace App\Http\Controllers\Api\Production;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Production\ProductionOrder\StatusChainProductionOrderRequest;
use App\Http\Resources\Production\ProductionOrderResource;
use App\Models\Production\ProductionOrder;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', ProductionOrder::class)) {
            abort(404);
        }

        $plans = ProductionOrder::latest()->paginate(10);
        return ApiResponse::sendResponse(
            'Successfully fetched orders',
            ProductionOrderResource::collection($plans),
            true,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionOrder $productionOrder, Request $request)
    {
        if ($request->user()->cannot('view', $productionOrder)) {
            abort(404);
        }

        return ApiResponse::sendResponse(
            'Successfully fetch an order',
            new ProductionOrderResource($productionOrder),
            true,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeStatus(
        StatusChainProductionOrderRequest $request,
        ProductionOrder $productionOrder
    ) {
        if ($request->user()->cannot('update', $productionOrder)) {
            abort(404);
        }

        $validated = $request->validated();
        $productionOrder->logNotes = $validated['notes'] ?? null;
        $productionOrder->status = $validated['status'];

        if (isset($validated['quantity_actual'])) {
            $productionOrder->quantity_actual = $validated['quantity_actual'];
        }
        if (isset($validated['quantity_rejected'])) {
            $productionOrder->quantity_rejected = $validated['quantity_rejected'];
        }

        $productionOrder->save();

        return ApiResponse::sendResponse(
            'Successfully updated plan',
            new ProductionOrderResource($productionOrder),
            true,
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionOrder $productionOrder)
    {
        //
    }
}
