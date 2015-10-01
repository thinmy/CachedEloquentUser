<?php 

namespace Thinmy\CachedEloquentUser;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('cachedEloquent',function()
        {
            $model = $this->app['config']['auth.model'];
            return new CachedEloquentUserProvider($this->app['hash'], $model);
        });
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