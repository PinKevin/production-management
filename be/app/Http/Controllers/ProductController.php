<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Resources\ProductResource;
use App\Models\PPIC\ProductionPlan;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('create', ProductionPlan::class)) {
            abort(403);
        }

        $products = Product::all();
        return ApiResponse::sendResponse(
            'Fetched product successfully',
            ProductResource::collection($products),
            true,
            200
        );
    }
}
