<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Policies\WarehousePolicy;
use App\Policies\SupplierPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\ActivityPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SalePolicy;
use App\Models\Warehouse;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Sale;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Warehouse::class => WarehousePolicy::class,
        // Category::class => CategoryPolicy::class,
        // Supplier::class => SupplierPolicy::class,
        // Purchase::class => PurchasePolicy::class,
        // Activity::class => ActivityPolicy::class,
        // Product::class => ProductPolicy::class,
        // Payment::class => PaymentPolicy::class,
        // Sale::class => SalePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
