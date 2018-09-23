<?php

namespace Merkeleon\SMS\Providers;


use Illuminate\Support\ServiceProvider;
use Merkeleon\SMS\Manager;

class SMSServiceProvider extends ServiceProvider
{
    /*
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->bind('sms', function ($app) {
            $manager = new Manager($app);

            return $manager->driver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sms', 'Merkeleon\SMS'];
    }
}