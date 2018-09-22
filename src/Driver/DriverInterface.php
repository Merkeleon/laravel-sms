<?php

namespace Merkeleon\SMS\Driver;


interface DriverInterface
{
    public function send($to, $text);
}