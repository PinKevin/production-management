<?php

namespace App\Http\Controllers\Api\PPIC;

use App\ApiResponse;
use App\Enum\PlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PPIC\ProductionPlan\ApproveProductionPlanRequest;
use App\Http\Requests\PPIC\ProductionPlan\StoreProductionPlanRequest;
use App\Http\Requests\PPIC\ProductionPlan\UpdateProductionPlanRequest;
use App\Models\PPIC\ProductionPlan;
use App\Http\Resources\PPIC\ProductionPlanResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', ProductionPlan::class)) {
            abort(404);
        }

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
        if ($request->user()->cannot('create', ProductionPlan::class)) {
            abort(404);
        }

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
    public function show(ProductionPlan $productionPlan, Request $request)
    {
        if ($request->user()->cannot('view', $productionPlan)) {
            abort(404);
        }

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
        if ($request->user()->cannot('update', $productionPlan)) {
            abort(404);
        }

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
    public function destroy(ProductionPlan $productionPlan, Request $request)
    {
        if ($request->user()->cannot('delete', $productionPlan)) {
            abort(404);
        }

        $productionPlan->delete();

        return ApiResponse::sendResponse(
            'Successfully deleted plan',
            [],
            true,
            200
        );
    }

    public function approvePlan(ProductionPlan $productionPlan, ApproveProductionPlanRequest $request)
    {
        if ($request->user()->cannot('approvePlan', $productionPlan)) {
            abort(404);
        }

        $validated = $request->validated();

        $deadlineInput = $validated['deadline'] ?? null;
        $deadline = $deadlineInput
            ? Carbon::parse($deadlineInput)->setTime(16, 0, 0)
            : now()->addDays(7)->setTime(16, 0, 0);

        $productionPlan->update([
            ...$validated,
            'deadline' => $deadline
        ]);

        return ApiResponse::sendResponse(
            'Successfully approved plan',
            new ProductionPlanResource($productionPlan),
            true,
            200
        );
    }
}
