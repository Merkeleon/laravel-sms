<?php

namespace Merkeleon\SMS;

use Illuminate\Support\Manager as BaseManager;
use Merkeleon\SMS\Driver\Clickatell;


class Manager extends BaseManager
{
    public function createClickatellDriver()
    {
        return new Clickatell(config('sms.drivers.clickatell'));
    }

    public function getDefaultDriver()
    {
        return config('sms.default');
    }

}