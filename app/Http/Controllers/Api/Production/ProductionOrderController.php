<?php

namespace App\Http\Controllers\Api\Production;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Production\ProductionOrder\StatusChainProductionOrderRequest;
use App\Http\Resources\Production\ProductionOrderResource;
use App\Models\Production\ProductionOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', ProductionOrder::class)) {
            abort(403);
        }

        $status = $request->input('status');
        $sortField = $request->input('sort-field');
        $sortOrder = $request->input('sort-order', 'desc');

        $query = ProductionOrder::with('product');
        if ($status) {
            $query->where('status', $status);
        }

        if ($sortField) {
            if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
                $sortOrder = 'desc';
            }
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->latest();
        }


        $orders = $query->paginate(10);

        return ApiResponse::sendResponse(
            'Successfully fetched orders',
            ProductionOrderResource::collection($orders),
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
            abort(403);
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
            abort(403);
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

    public function makeReport(Request $request)
    {
        if ($request->user()->cannot('makeReport', ProductionOrder::class)) {
            abort(403);
        }

        $orderId = $request->input('order-id');
        if ($orderId) {
            $order = ProductionOrder::with(['product', 'productionLogs.user'])
                ->find($orderId);

            if (!$order) {
                abort(403);
            }

            $data = [
                'order' => $order
            ];

            $pdf = Pdf::loadView('reports.production_order_single', $data);
            return $pdf->stream("laporan-order-produksi-{$order->id}");
        }

        $period = $request->input('period', 'weekly');
        $dateInput = $request->input('date');

        $date = Carbon::parse($dateInput ?? now());

        if ($period == 'weekly') {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek();
        } else {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
        }

        $startDateFormatted = $startDate->format('d-m-Y');
        $endDateFormatted = $endDate->format('d-m-Y');

        $orders = ProductionOrder::with('product')
            ->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->latest()
            ->get();

        $data = [
            'orders' => $orders,
            'startDateFormatted' => $startDateFormatted,
            'endDateFormatted' => $endDateFormatted
        ];

        $pdf = Pdf::loadView('reports.production_order_periodic', $data);
        return $pdf->stream("laporan-order-produksi-{$startDateFormatted}-{$endDateFormatted}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionOrder $productionOrder)
    {
        //
    }
}
