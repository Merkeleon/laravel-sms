<?php
/**
 * Created by PhpStorm.
 * User: andreikorsak
 * Date: 2018-09-21
 * Time: 17:29
 */

namespace Merkeleon\SMS\Providers;


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