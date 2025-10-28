<?php

namespace App\Http\Controllers\Api\PPIC;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\PPIC\ProductionPlan;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionPlan $productionPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionPlanRequest $request, ProductionPlan $productionPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionPlan $productionPlan)
    {
        //
    }
}
