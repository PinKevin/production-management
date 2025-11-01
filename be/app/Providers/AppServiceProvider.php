<?php

namespace App\Providers;

use App\Models\PPIC\ProductionPlan;
use App\Models\Production\ProductionOrder;
use App\Observers\ProductionOrderObserver;
use App\Policies\ProductionPlanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(ProductionPlan::class, ProductionPlanPolicy::class);

        ProductionOrder::observe(ProductionOrderObserver::class);
    }
}
