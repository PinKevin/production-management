<?php

use App\Enum\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('production_plan_id');
            $table->integer('quantity_planned');
            $table->integer('quantity_actual')->nullable();
            $table->integer('quantity_rejected')->nullable();
            $table->enum('status', Arr::pluck(OrderStatus::cases(), 'value'));
            $table->date('deadline')->nullable();
            $table->timestamps();

            $table->foreign('production_plan_id')->references('id')->on('production_plans');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
