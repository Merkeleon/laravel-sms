<?php
/**
 * Created by PhpStorm.
 * User: andreikorsak
 * Date: 2018-09-21
 * Time: 17:23
 */

namespace Merkeleon\SMS;

use Illuminate\Support\Manager as BaseManager;
use Merkeleon\SMS\Driver\Clickatell;


class Manager extends BaseManager
{
    public function createClickatellDriver()
    {
        return new Clickatell(config('sms.driver.clickatell.options'));
    }

    public function getDefaultDriver()
    {
        return config('sms.driver');
    }

}