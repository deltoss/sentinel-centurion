<?php
namespace Deltoss\Centurion\Providers;

use Deltoss\Centurion\Console\CenturionSpruce;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Cartalyst\Sentinel\Laravel\SentinelServiceProvider;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Deltoss\SentinelDatabasePermissions\Providers\SentinelDatabasePermissionsServiceProvider;

use Deltoss\Centurion\Http\Middleware\CenturionAuthenticate;
use Deltoss\Centurion\Http\Middleware\CenturionCheckAllPermissions;
use Deltoss\Centurion\Http\Middleware\CenturionCheckAnyPermissions;
use Deltoss\Centurion\Http\Middleware\CenturionCheckRole;
use Deltoss\Centurion\Http\Middleware\CenturionRedirectIfAuthenticated;

class CenturionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Artisan Commands
        $this->registerArtisanCommands();
        $this->registerMiddlewares();
        $this->prepareResources();
    }

    /**
     * Registers the package middlewares.
     *
     * @return void
     */
    public function registerMiddlewares()
    {
        $this->app['router']->aliasMiddleware('centurion.guest', CenturionRedirectIfAuthenticated::class);
        $this->app['router']->aliasMiddleware('centurion.auth', CenturionAuthenticate::class);
        $this->app['router']->aliasMiddleware('centurion.hasaccess', CenturionCheckAllPermissions::class);
        $this->app['router']->aliasMiddleware('centurion.hasanyaccess', CenturionCheckAnyPermissions::class);
        $this->app['router']->aliasMiddleware('centurion.hasrole', CenturionCheckRole::class);
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    public function prepareResources()
    {
        // Load the configuration file, and configure them to be publishable
        if (file_exists(config_path('centurion.php')))
        {
            $this->mergeConfigFrom(config_path('centurion.php'), 'centurion');
        }
        else
        {
            $this->mergeConfigFrom(__DIR__ . '/../../publishable/config/centurion.php', 'centurion');
        }

        $this->publishes([
            __DIR__ . '/../../publishable/config/centurion.php' => config_path('centurion.php'),
        ], 'config');

        // Load the views, and configure them to be publishable
        if (is_dir(resource_path('/views/vendor/centurion')))
            $this->loadViewsFrom(resource_path('/views/vendor/centurion'), 'centurion');
        else
            $this->loadViewsFrom(__DIR__ . '/../../publishable/resources/views', 'centurion');

        $this->publishes([
            __DIR__ . '/../../publishable/resources/views' => resource_path('views/vendor/centurion'),
        ], 'views');

        // Load the language translations, and configure them to be publishable
        if (is_dir(resource_path('/lang/vendor/centurion')))
            $this->loadTranslationsFrom(resource_path('/lang/vendor/centurion'), 'centurion');
        else
            $this->loadTranslationsFrom(__DIR__ . '/../../publishable/resources/lang', 'centurion');

        $this->publishes([
            __DIR__ . '/../../publishable/resources/lang' => resource_path('lang/vendor/centurion'),
        ], 'translations');

        // Load the routes, and configure them to be publishable
        if (file_exists(base_path('/routes/vendor/centurion/web.php')))
            $this->loadRoutesFrom(base_path('/routes/vendor/centurion/web.php'));
        else
            $this->loadRoutesFrom(__DIR__ . '/../../publishable/routes/web.php');

        $this->publishes([
            __DIR__ . '/../../publishable/routes/web.php' => base_path('/routes/vendor/centurion/web.php'),
        ], 'routes');

        // Configure assets to be publishable
        $this->publishes([
            __DIR__ . '/../../publishable/public' => public_path('vendor/centurion'),
        ], 'public');

        // Load the migrations, and configure them to be publishable
        if (is_dir(base_path('/database/vendor/centurion/migrations')))
            $this->loadMigrationsFrom(base_path('/database/vendor/centurion/migrations'));
        else
            $this->loadMigrationsFrom(__DIR__ . '/../../publishable/database/migrations');
            
        $this->publishes([
            __DIR__ . '/../../publishable/database/migrations' => base_path('/database/vendor/centurion/migrations'),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the required Service Provider
        $this->app->register(SentinelDatabasePermissionsServiceProvider::class);

        // Load the Sentinel Facade Aliases
        $loader = AliasLoader::getInstance();
        $loader->alias('Activation', Activation::class);
        $loader->alias('Reminder', Reminder::class);
        $loader->alias('Sentinel', Sentinel::class);
    }

    /**
     * Register the Artisan Commands
     */
    private function registerArtisanCommands()
    {   
        // Register the Spruce command
        $this->app->singleton('centurion.spruce', function ($app) {
            return new CenturionSpruce(
                $app->make('files')
            );
        });
        $this->commands('centurion.spruce');
    }
}