# Laravel SMS
Laravel module for send SMS  


## Installation

To install from Composer, use the following command:

`composer require merkeleon/laravel-sms`

Now, add the following optional line(s) to your `.env` file:

```
# default driver for writing sms message in log file
SMS_DRIVER=log
```

Add the sms.php config:

`php artisan vendor:publish --provider="Merkeleon\SMS\Providers\SMSServiceProvider"`

Next, add the facade to your aliases array in Laravel as follows:

```
<?php
return [
    'aliases' => [
        //...
        'SMS' => Merkeleon\SMS\Facades\SMS::class
        //...
    ],
];

```

## Usage Examples

### Send SMS 
      
```
SMS::send('+0000000000', 'SMS message!');
```

## Available drivers

The following drivers are available for sending messages:

 - log: (default) writing sms message in log file
```
SMS_DRIVER=log
```

 - [Сlickatell] (accounts created before November 2016)
 ```
 SMS_DRIVER=clickatellV1
 SMS_CLICKATELL_TOKEN=your_token_value
 ```
 
 - [Сlickatell] (accounts created after November 2016)
 ```
 SMS_DRIVER=clickatellV2
 SMS_CLICKATELL_TOKEN=token_value
 ```
 
 - [Nexmo] 
 ```
 SMS_DRIVER=nexmo
 SMS_NEXMO_KEY=your_key_value
 SMS_NEXMO_SENDER=your_secret_value
 SMS_NEXMO_SENDER=your_sender_value
 SMS_NEXMO_MAX_ATTEMPT_NUMBER=10
 ```
 

- [SMS центр]

```
SMS_DRIVER=smsc
SMS_SMSC_LOGIN=your_login_value
SMS_SMSC_PASSWORD=your_password_value
SMS_SMSC_SENDER=your_sender_value
```
 

- [Cryptosasa]

```
SMS_DRIVER=cryptosasa
SMS_CRYPTOSASA_URL=cryptosasa_api_url_value
SMS_CRYPTOSASA_USERNAME=your_username_value
SMS_CRYPTOSASA_SECRET=your_secret_value
```


[Сlickatell]: <https://www.clickatell.com>
[Nexmo]: <https://www.nexmo.com>
[SMS центр]: <https://smsc.ru>
[Cryptosasa]: <https://www.cryptosasa.com/>

