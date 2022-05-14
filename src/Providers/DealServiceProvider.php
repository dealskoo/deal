<?php

namespace Dealskoo\Deal\Providers;

use Dealskoo\Admin\Facades\AdminMenu;
use Dealskoo\Admin\Facades\PermissionManager;
use Dealskoo\Admin\Permission;
use Dealskoo\Seller\Facades\SellerMenu;
use Illuminate\Support\ServiceProvider;

class DealServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/deal')
            ], 'lang');
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/seller.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'deal');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'deal');

        AdminMenu::route('admin.deals.index', 'deal::deal.deals', [], ['icon' => 'uil-gold', 'permission' => 'deals.index'])->order(2);

        PermissionManager::add(new Permission('deals.index', 'Deal List'));
        PermissionManager::add(new Permission('deals.show', 'View Deal'), 'deals.index');
        PermissionManager::add(new Permission('deals.edit', 'Edit Deal'), 'deals.index');

        SellerMenu::route('seller.deals.index', 'deal::deal.deals', [], ['icon' => 'uil-gold me-1'])->order(3);
    }
}
