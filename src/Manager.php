<?php

namespace Merkeleon\SMS;

use Illuminate\Support\Manager as BaseManager;
use Merkeleon\SMS\Driver\ClickatellV1;
use Merkeleon\SMS\Driver\ClickatellV2;
use Merkeleon\SMS\Driver\Cryptosasa;
use Merkeleon\SMS\Driver\Log;
use Merkeleon\SMS\Driver\Nexmo;
use Merkeleon\SMS\Driver\Smsc;


class Manager extends BaseManager
{
    public function createClickatellV1Driver()
    {
        return new ClickatellV1(config('sms.drivers.clickatellV1'));
    }

    public function createClickatellV2Driver()
    {
        return new ClickatellV2(config('sms.drivers.clickatellV2'));
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

    public function createCryptosasaDriver()
    {
        return new Cryptosasa(config('sms.drivers.cryptosasa'));
    }

    public function getDefaultDriver()
    {
        return config('sms.default');
    }

}