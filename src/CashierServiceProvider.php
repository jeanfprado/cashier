<?php

namespace Jeanfprado\Cashier;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jeanfprado\Console\CashierSeedPlans;
use Jeanfprado\Cashier\Gateway\GatewayManager;
use Gerencianet\Gerencianet as GerencianetClient;
use Jeanfprado\Cashier\Gateway\Drivers\Gerencianet;

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
        $this->registerClients();
        $this->registerGateways();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();
        // $this->registerCommands();
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
     * Register the gateways clients
     *
     * @return void
     */
    protected function registerClients()
    {
        $this->app->singleton(GerencianetClient::class, function ($app) {
            return new GerencianetClient([
                'client_id' => $app['config']['gateway.gateways.gerencianet.client_id'],
                'client_secret' => $app['config']['gateway.gateways.gerencianet.client_secret'],
                'sandbox' => $app['config']['gateway.gateways.gerencianet.is_sandbox'],
            ]);
        });
    }

    /**
     * Register the gateways clients
     *
     * @return void
     */
    protected function registerGateways()
    {
        $this->app->singleton('gerencianet', function ($app) {
            return new Gerencianet(
                $app[GerencianetClient::class],
            );
        });

        $this->app->singleton('gateway', function ($app) {
            return new GatewayManager($app);
        });
    }

     /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        // if (Cashier::$registersRoutes) {
        Route::group([
            'namespace' => 'Jeanfprado\Cashier\Http\Controllers',
            'as' => 'cashier.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
        // }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cashier.php' => $this->app->configPath('cashier.php'),
            ], 'cashier-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'cashier-migrations');
        }
    }
}
