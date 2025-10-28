<?php

namespace App\Http\Controllers\Api\PPIC;

use App\ApiResponse;
use App\Enum\OrderStatus;
use App\Enum\PlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PPIC\ProductionPlan\ApproveProductionPlanRequest;
use App\Http\Requests\PPIC\ProductionPlan\StoreProductionPlanRequest;
use App\Http\Requests\PPIC\ProductionPlan\UpdateProductionPlanRequest;
use App\Models\PPIC\ProductionPlan;
use App\Http\Resources\PPIC\ProductionPlanResource;
use App\Models\Production\ProductionOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $validated = $request->validated();
        $plan = ProductionPlan::create($validated);
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
    public function update(
        UpdateProductionPlanRequest $request,
        ProductionPlan $productionPlan
    ) {
        if ($request->user()->cannot('update', $productionPlan)) {
            abort(404);
        }

        $validated = $request
            ->safe()
            ->only([
                'quantity',
                'product_id',
                'notes',
                'status'
            ]);

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

    public function approvePlan(
        ProductionPlan $productionPlan,
        ApproveProductionPlanRequest $request
    ) {
        if ($request->user()->cannot('approvePlan', $productionPlan)) {
            abort(404);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $deadlineInput = $validated['deadline'] ?? null;
            $deadline = $deadlineInput
                ? Carbon::parse($deadlineInput)->setTime(16, 0, 0)
                : now()->addDays(7)->setTime(16, 0, 0);

            $productionPlan->update([
                ...$validated,
                'deadline' => $deadline
            ]);

            if ($validated['status'] == PlanStatus::APPROVED->value) {
                ProductionOrder::create([
                    'product_id' => $productionPlan->product_id,
                    'production_plan_id' => $productionPlan->id,
                    'quantity_planned' => $productionPlan->quantity,
                    'status' => OrderStatus::WAITING,
                    'deadline' => $deadline,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::sendResponse(
                'Failed to approve plan',
                $e->getMessage(),
                false,
                500
            );
        }

        return ApiResponse::sendResponse(
            'Successfully approved plan',
            new ProductionPlanResource($productionPlan),
            true,
            200
        );
    }

    public function makeReport(Request $request)
    {
        if ($request->user()->cannot('makeReport', ProductionPlan::class)) {
            abort(404);
        }

        $period = $request->input('period', 'weekly');
        $dateInput = $request->input('date');

        $date = Carbon::parse($dateInput);

        if ($period == 'weekly') {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek();
        } else {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
        }

        $startDateFormatted = $startDate->format('d-m-Y');
        $endDateFormatted = $endDate->format('d-m-Y');

        $plans = ProductionPlan::with('product')
            ->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->latest()
            ->get();

        $data = [
            'plans' => $plans,
            'startDateFormatted' => $startDateFormatted,
            'endDateFormatted' => $endDateFormatted
        ];

        $pdf = Pdf::loadView('reports.production_plan', $data);
        return $pdf->stream("laporan-produksi-{$startDateFormatted}-{$endDateFormatted}.pdf");
    }
}
