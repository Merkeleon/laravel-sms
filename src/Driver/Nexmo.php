<?php

namespace Merkeleon\SMS\Driver;


use Merkeleon\SMS\Exception;

class Nexmo implements DriverInterface
{
    private $apiKey    = '';
    private $apiSecret = '';
    private $sender    = '';

    private $currentAttemptNumber = 0;
    private $maxAttemptNumber     = 0;

    const MAX_ATTEMPT_NUMBER = 3;

    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public function __construct($config = [])
    {
        $this->apiKey           = array_get($config, 'api_key');
        $this->apiSecret        = array_get($config, 'api_secret');
        $this->sender           = array_get($config, 'sender');
        $this->maxAttemptNumber = array_get($config, 'max_attempt_number', self::MAX_ATTEMPT_NUMBER);
    }

    public function send($to, $text)
    {
        try
        {
            ++$this->currentAttemptNumber;
            $result = $this->sendMessage($to, $text);

            return array_get($result, 'message-id');
        }
        catch (Exception $e)
        {
            logger()->error('SMS Exception: ' . $e->getMessage() . ': ' . $e->getFile() . ' / ' . $e->getLine());
        }

        return false;
    }

    private function curl($to, $message)
    {
        $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
                'api_key'    => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'to'         => $to,
                'from'       => $this->sender,
                'text'       => $message,
            ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!$response = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }

    private function sendMessage($to, $message)
    {
        $response = $this->curl($to, $message);

        $response = json_decode($response, true);

        if (json_last_error())
        {
            throw new Exception(
                'Cannot prettify invalid json'
            );
        }

        if (!isset($response['messages']))
        {
            throw new Exception('unexpected response from API');
        }
        $responseMessages = array_first(array_get($response, 'messages'));

        $responseStatus    = array_get($responseMessages, 'status');
        $responseErrorText = array_get($responseMessages, 'error-text');

        switch ($responseStatus)
        {
            case '0':
                continue; //all okay
            case '1':
                if ($this->currentAttemptNumber < $this->maxAttemptNumber)
                {
                    if (preg_match('#\[\s+(\d+)\s+\]#', $responseErrorText, $match))
                    {
                        usleep($match[1] + 1);
                    }
                    else
                    {
                        sleep(1);
                    }

                    return $this->send($to, $message);
                }
                throw new Exception('exceeded the limit of attempts to send SMS. max: ' . $this->maxAttemptNumber);
            default:
                throw new Exception($responseErrorText);
        }

        return $response;
    }

}
