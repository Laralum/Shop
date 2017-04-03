<?php

namespace Laralum\Shop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Laralum\Shop\Models\Category;
use Laralum\Shop\Policies\CategoryPolicy;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Policies\ItemPolicy;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Policies\OrderPolicy;
use Laralum\Shop\Models\Status;
use Laralum\Shop\Policies\StatusPolicy;

use Laralum\Permissions\PermissionsChecker;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Item::class => ItemPolicy::class,
        Order::class => OrderPolicy::class,
        Status::class => StatusPolicy::class,
    ];

    /**
     * The mandatory permissions for the module.
     *
     * @var array
     */
    protected $permissions = [
        [
            'name' => 'Shop Categories Access',
            'slug' => 'laralum::shop.category.access',
            'desc' => "Grants access to shop categories",
        ],
        [
            'name' => 'Create Shop Categories',
            'slug' => 'laralum::shop.category.create',
            'desc' => "Allows creating shop categories",
        ],
        [
            'name' => 'Edit Shop Categories',
            'slug' => 'laralum::shop.category.update',
            'desc' => "Allows editing shop categories",
        ],
        [
            'name' => 'Delete Shop Categories',
            'slug' => 'laralum::shop.category.delete',
            'desc' => "Allows deleting shop categories",
        ],
        [
            'name' => 'Shop Items Access',
            'slug' => 'laralum::shop.item.access',
            'desc' => "Grants access to shop items",
        ],
        [
            'name' => 'Create Shop Items',
            'slug' => 'laralum::shop.item.create',
            'desc' => "Allows creating shop items",
        ],
        [
            'name' => 'Edit Shop Items',
            'slug' => 'laralum::shop.item.update',
            'desc' => "Allows editing shop items",
        ],
        [
            'name' => 'Delete Shop Items',
            'slug' => 'laralum::shop.item.delete',
            'desc' => "Allows deleting shop items",
        ],
        [
            'name' => 'View Shop Orders',
            'slug' => 'laralum::shop.order.access',
            'desc' => 'Grants access to the shop orders',
        ],
        [
            'name' => 'Change Shop Order Status',
            'slug' => 'laralum::shop.order.status',
            'desc' => 'Allows changing the shop order status',
        ],
        [
            'name' => 'Shop Status Access',
            'slug' => 'laralum::shop.status.access',
            'desc' => "Grants access to shop status",
        ],
        [
            'name' => 'Create Shop Status',
            'slug' => 'laralum::shop.status.create',
            'desc' => "Allows creating shop status",
        ],
        [
            'name' => 'Edit Shop Status',
            'slug' => 'laralum::shop.status.update',
            'desc' => "Allows editing shop status",
        ],
        [
            'name' => 'Delete Shop Status',
            'slug' => 'laralum::shop.status.delete',
            'desc' => "Allows deleting shop status",
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->loadTranslationsFrom(__DIR__.'/Translations', 'laralum_shop');

        $this->loadViewsFrom(__DIR__.'/Views', 'laralum_shop');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/web.php';
        }

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        // Make sure the permissions are OK
        PermissionsChecker::check($this->permissions);
    }

    /**
     * I cheated this comes from the AuthServiceProvider extended by the App\Providers\AuthServiceProvider
     *
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
