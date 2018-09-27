<?php

namespace Merkeleon\SMS\Driver;


use Merkeleon\SMS\Exception;

class Smsc implements DriverInterface
{
    const FMT_XML  = 2;
    const FMT_JSON = 3;

    const CHARSET_UTF8 = 'utf-8';
    const CHARSET_KOI8 = 'koi8-r';
    const CHARSET_1251 = 'windows-1251';

    private $login;
    private $password;
    private $useSSL;
    private $sender;
    private $options = [];
    private $types   = ['', 'flash=1', 'push=1', 'hlr=1', 'bin=1', 'bin=2', 'ping=1'];

    private static $curl = null;

    /**
     * Smsc constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->login    = array_get($config, 'login');
        $this->password = array_get($config, 'password');
        $this->sender   = array_get($config, 'sender');
        $this->useSSL   = array_get($config, 'useSSL', true);
        $default        = [
            'charset' => self::CHARSET_UTF8,
            'fmt'     => self::FMT_JSON
        ];
        $this->options  = array_merge($default, array_get($config, 'options', []));
    }

    /**
     * @param $to
     * @param $text
     * @param null $sender
     * @param array $options
     *
     * @return bool|\stdClass|string
     * @throws Exception
     */
    public function send($to, $text, array $options = [])
    {

        if ($text !== null && empty($text))
        {
            throw new Exception('The message is empty.');
        }
        elseif (mb_strlen($text, 'UTF-8') > 800)
        {
            throw new Exception('The maximum length of a message is 800 symbols.');
        }
        $options['phones'] = $to;
        $options['mes']    = $text;
        if ($this->sender !== null)
        {
            $options['sender'] = $this->sender;
        }

        return $this->sendMessage('send', $options);
    }


    /**
     * @param $resource
     * @param array $options
     *
     * @return bool|null|string|string[]
     */
    private function sendMessage($resource, array $options)
    {
        $options = array_merge($this->options, $options);

        $params = [
            'login=' . urlencode($this->login),
            'psw=' . urlencode($this->password)
        ];
        foreach ($options as $key => $value)
        {
            switch ($key)
            {
                case 'type':
                    if ($value > 0 && $value < count($this->types))
                    {
                        $params[] = $this->types[$value];
                    }
                    break;
                default:
                    if (!empty($value))
                    {
                        $params[] = $key . '=' . urlencode($value);
                    }
            }
        }
        $i = 0;
        do
        {
            (!$i) || sleep(2);

            $ret = $this->execRequest($resource, $params);
        } while ($ret == '' && ++$i < 3);

        return !empty($ret) ? $ret : false;
    }

    /**
     * @param $resource
     * @param array $params
     *
     * @return bool|mixed|string
     */
    private function execRequest($resource, array $params)
    {
        $url    = ($this->useSSL ? 'https' : 'http') . '://smsc.ru/sys/' . $resource . '.php';
        $query  = implode('&', $params);
        $isPOST = $resource === 'send' ? true : false;
        if (function_exists('curl_init'))
        {
            if (!self::$curl)
            {
                self::$curl = curl_init();
                curl_setopt_array(self::$curl, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_TIMEOUT        => 10
                ]);
            }
            curl_setopt_array(self::$curl, [
                CURLOPT_URL        => $url,
                CURLOPT_POST       => true,
                CURLOPT_POSTFIELDS => $query,
            ]);
            $response = curl_exec(self::$curl);
        }
        else
        {
            $options = [
                'timeout' => 5,
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $query,
            ];

            $response = file_get_contents($url, false, stream_context_create(['http' => $options]));
        }

        return $response;
    }
}
