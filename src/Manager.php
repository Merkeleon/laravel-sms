<?php

namespace Merkeleon\SMS;

use Illuminate\Support\Manager as BaseManager;
use Merkeleon\SMS\Driver\Clickatell;
use Merkeleon\SMS\Driver\Log;
use Merkeleon\SMS\Driver\Nexmo;
use Merkeleon\SMS\Driver\Smsc;


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

    public function createNexmoDriver()
    {
        return new Nexmo(config('sms.drivers.nexmo'));
    }

    public function createSmscDriver()
    {
        return new Smsc(config('sms.drivers.smsc'));
    }

    public function getDefaultDriver()
    {
        return config('sms.default');
    }

}