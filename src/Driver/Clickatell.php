<?php

namespace Merkeleon\SMS\Driver;


use Merkeleon\SMS\Exception;

class Clickatell implements DriverInterface
{
    /**
     * API base URL
     * @var string
     */
    const API_URL = 'https://platform.clickatell.com/';
    /**
     * @var string
     */
    const HTTP_GET = 'GET';
    /**
     * @var string
     */
    const HTTP_POST = 'POST';
    /**
     * The CURL agent identifier
     * @var string
     */
    const AGENT = 'ClickatellV2/1.0';
    /**
     * Excepted HTTP statuses
     * @var string
     */
    const ACCEPTED_CODES = '200, 201, 202';
    /**
     * @var string
     */
    private $apiToken = '';

    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public function __construct(array $config = [])
    {
        $this->apiToken = array_get($config, 'token');
    }

    /**
     * Handle CURL response from Clickatell APIs
     *
     * @param string $result The API response
     * @param int $httpCode The HTTP status code
     *
     * @throws Exception
     * @return array
     */
    protected function handle($result, $httpCode)
    {
        // Check for non-OK statuses
        $codes = explode(",", static::ACCEPTED_CODES);
        if (!in_array($httpCode, $codes))
        {
            // Decode JSON if possible, if this can't be decoded...something fatal went wrong
            // and we will just return the entire body as an exception.
            if ($error = json_decode($result, true))
            {
                $error = array_get($error, '0.error', array_get($error, 'error.description'));
            }
            else
            {
                $error = $result;
            }

            throw new Exception($error);
        }
        else
        {
            return json_decode($result, true);
        }
    }

    /**
     * Abstract CURL usage.
     *
     * @param string $uri The endpoint
     * @param array $data Array of parameters
     *
     * @return array
     * @throws Exception
     */
    protected function curl($uri, array $data = [])
    {
        $headers = [
            'X-Version: 1',
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->apiToken
        ];

        // This is the clickatell endpoint. It doesn't really change so
        // it's safe for us to "hardcode" it here.
        $endpoint = static::API_URL . "/" . $uri;
        $curlInfo = curl_version();
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, static::AGENT . ' curl/' . $curlInfo['version'] . ' PHP/' . phpversion());
        // Specify the raw post data
        if ($data)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $this->handle($result, $httpCode);
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-send-message/
     *
     * @param array $message The message parameters
     *
     * @return array
     * @throws Exception
     */
    public function sendMessage(array $message)
    {
        $response = $this->curl('messages', $message);

        if ($error = array_get($response, 'error'))
        {
            throw new Exception($error);
        }

        return array_get($response, 'data.message.0');
    }

    public function send($to, $message)
    {
        try
        {
            $result = $this->sendMessage(['to' => [$to], 'content' => $message]);

            return array_get($result, 'accepted');

        }
        catch (Exception $e)
        {
            logger()->error('SMS Exception: ' . $e->getMessage() . ': ' . $e->getFile() . ' / ' . $e->getLine());
        }

        return false;
    }

}
