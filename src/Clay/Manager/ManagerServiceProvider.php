<?php

namespace Clay\Manager;

use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('manager', function($app) {
            return new ManagerFactory($app, $app['validator'], $app['redirect'], $app['request'], $app['events']);
        });
    }

}
