<?php

namespace Merkeleon\SMS;

use Illuminate\Support\Manager as BaseManager;
use Merkeleon\SMS\Driver\Clickatell;
use Merkeleon\SMS\Driver\Log;


class Manager extends BaseManager
{
    public function createClickatellDriver()
    {
        return new Clickatell(config('sms.drivers.clickatell'));
    }

    public function createLogDriver()
    {
        return new Log(config('sms.drivers.log'));
    }

    public function getDefaultDriver()
    {
        return config('sms.default');
    }

}