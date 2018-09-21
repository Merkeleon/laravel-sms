<?php
/**
 * Created by PhpStorm.
 * User: andreikorsak
 * Date: 2018-09-21
 * Time: 17:59
 */

namespace Merkeleon\SMS\Driver;


interface DriverInterface
{
    public function send($to, $text);
}