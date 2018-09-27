<?php

namespace Merkeleon\SMS\Providers;


use Illuminate\Support\ServiceProvider;
use Merkeleon\SMS\Manager;

class SMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/sms.php' => config_path('sms.php'),
        ]);
    }

    /*
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->bind('sms', function ($app) {
            $manager = new Manager($app);

            return $manager->driver(config('sms.driver'));
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/sms.php', 'sms'
        );
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