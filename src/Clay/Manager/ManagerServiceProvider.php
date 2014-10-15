<?php

namespace Manager;

use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['manager'] = $this->app->share(function ($app) {
            return new ManagerFactory($app, $app['validator'], $app['redirect'], $app['request'], $app['events']);
        });
    }

}
