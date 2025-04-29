<?php

namespace Bytecraftnz\Larabase;

use Illuminate\Support\Facades\Auth;

class LarabaseServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/larabase.php' => $this->app->configPath('larabase.php'),
        ], 'larabase');

        $this->bootSingletons();

        $this->bootAuthProviderAndGuard();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Register the singleton instances.
     *
     * @return void
     */
    protected function bootSingletons()
    {

        

        $this->app->singleton(\Bytecraftnz\Larabase\Contracts\AuthClient::class, function ($app, array $config) {
            return new \Bytecraftnz\Larabase\Clients\AuthClient(
                $config['url'],
                $config['key'],
                new \GuzzleHttp\Client
            );
        });

        $this->app->singleton(\Bytecraftnz\Larabase\Contracts\AdminClient::class, function ($app, array $config) {
            return new \Bytecraftnz\Larabase\Clients\AdminClient(
                $config['url'],
                $config['key'],
                $config['service_key'],
                new \GuzzleHttp\Client
            );
        });

        $this->app->singleton(\Bytecraftnz\Larabase\LarabaseUserProvider::class, function ($app, array $config) {

            return new \Bytecraftnz\Larabase\LarabaseUserProvider(
                $app->make(\Bytecraftnz\Larabase\Contracts\AuthClient::class),
                $app['session.store'],
            );
        });

        $this->app->singleton(\Bytecraftnz\Larabase\LarabaseGuard::class, function ($app, array $config) {

            return new \Bytecraftnz\Larabase\LarabaseGuard(
                $app->make(\Bytecraftnz\Larabase\LarabaseUserProvider::class),
                $app['session.store'],

            );
        });

    }

    /**
     * Register the auth provider and guard.
     *
     * @return void
     */
    protected function bootAuthProviderAndGuard()
    {
        $this->app['auth']->provider('larabase', function ($app, array $config) {
            return $app->make(\Bytecraftnz\Larabase\LarabaseUserProvider::class);
        });

        $this->app['auth']->extend('larabase', function ($app, $name, array $config) {
            return $app->make(\Bytecraftnz\Larabase\LarabaseGuard::class);
        });
    }
}
