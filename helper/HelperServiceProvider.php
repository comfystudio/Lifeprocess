<?php
namespace Helper;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Indicates of loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Helper\AppHelper'
        );

        $this->app->singleton('AppHelper', function () {
            return $this->app->make('Helper\AppHelper');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['AppHelper'];
    }
}
