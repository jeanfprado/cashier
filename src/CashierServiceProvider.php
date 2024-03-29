<?php

namespace Jeanfprado\Cashier;

use Illuminate\Support\ServiceProvider;
use Jeanfprado\Cashier\Console\CashierSeedPlans;
use Jeanfprado\Cashier\Console\CashierSubscriptionCheck;

class CashierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();
        $this->registerCommands();
        $this->registerPublishing();
    }

     /**
     * Setup the configuration for Cashier.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cashier.php',
            'cashier'
        );
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CashierSeedPlans::class,
                CashierSubscriptionCheck::class,
            ]);
        }
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/cashier.php' => $this->app->configPath('cashier.php'),
        ], 'cashier-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], 'cashier-migrations');
    }
}
