<?php

namespace App\Http\Controllers\Api\PPIC;

use App\ApiResponse;
use App\Enum\PlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PPIC\ProductionPlan\StoreProductionPlanRequest;
use App\Http\Requests\PPIC\ProductionPlan\UpdateProductionPlanRequest;
use App\Models\PPIC\ProductionPlan;
use App\Http\Resources\PPIC\ProductionPlanResource;

class ProductionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = ProductionPlan::latest()->paginate(10);
        return ApiResponse::sendResponse(
            'Successfully fetched plans',
            ProductionPlanResource::collection($plans),
            true,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionPlanRequest $request)
    {
        $input = $request->validated();
        $plan = ProductionPlan::create([
            ...$input,
            'status' => PlanStatus::CREATED,
        ]);
        return ApiResponse::sendResponse(
            'Successfully created new plan',
            new ProductionPlanResource($plan),
            true,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionPlan $productionPlan)
    {
        return ApiResponse::sendResponse(
            'Successfully fetch a plan',
            new ProductionPlanResource($productionPlan),
            true,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionPlanRequest $request, ProductionPlan $productionPlan)
    {
        $validated = $request->safe()->only(['quantity', 'product_id', 'notes']);

        $productionPlan->update($validated);

        return ApiResponse::sendResponse(
            'Successfully updated plan',
            new ProductionPlanResource($productionPlan),
            true,
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionPlan $productionPlan)
    {
        $productionPlan->delete();

        return ApiResponse::sendResponse(
            'Successfully deleted plan',
            [],
            true,
            200
        );
    }
}
