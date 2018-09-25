<?php

namespace Merkeleon\SMS\Driver;

class Log implements DriverInterface
{
    public function send($to, $message)
    {
        logger()->info('Log sms', [
            'to'      => $to,
            'message' => $message,
        ]);

        return true;
    }

}
