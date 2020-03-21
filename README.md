Yii2 graylog log target
=======================
Graylog log target for Yii2

[![Latest Stable Version](https://poser.pugx.org/yiier/yii2-graylog-target/v/stable)](https://packagist.org/packages/yiier/yii2-graylog-target) 
[![Total Downloads](https://poser.pugx.org/yiier/yii2-graylog-target/downloads)](https://packagist.org/packages/yiier/yii2-graylog-target) 
[![Latest Unstable Version](https://poser.pugx.org/yiier/yii2-graylog-target/v/unstable)](https://packagist.org/packages/yiier/yii2-graylog-target) 
[![License](https://poser.pugx.org/yiier/yii2-graylog-target/license)](https://packagist.org/packages/yiier/yii2-graylog-target)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiier/yii2-graylog-target "*"
```

or add

```
"yiier/yii2-graylog-target": "*"
```

to the require section of your `composer.json` file.


## Configuration

```php
return [
    'components' => [
        'log' => [
            'targets' => [
                'graylog' => [
                    'class' => yiier\graylog\Target::class,
                        'levels' => ['error', 'warning', 'info'],
                        // 'categories' => ['application', 'graylog'],
                        // 'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                        // 'facility' => 'facility-name',
                        'transport' => [
                            'class' => yiier\graylog\transport\UdpTransport::class,
                            'host' => '127.0.0.1',
                            'port' => '1231',
                            'chunkSize' => 4321,
                        ],
                        'additionalFields' => [
                            'user-ip' => function ($yii) {
                                return ($yii instanceof \yii\console\Application) ? '' : $yii->request->userIP;
                            },
                            'tag' => 'tag-name'
                        ],
                    ],
            ],
        ],
    ],
];
```

## Transport

### UDP transport

```php
$transport = new yiier\graylog\transport\UdpTransport([
    // Host name or IP. Default to 127.0.0.1
    'host' => 'graylog.example.org',
    // UDP port. Default to 12201
    'port' => 1234,
    // UDP chunk size. Default to 8154
    'chunkSize' => 4321,
]);
```

### TCP transport

```php
$transport = new yiier\graylog\transport\UdpTransport([
    // Host name or IP. Default to 127.0.0.1
    'host' => 'graylog.example.org',
    // TCP port. Default to 12201
    'port' => 12201,
    // SSL options. (optional)
    'sslOptions' => [
        // Default to true
        'verifyPeer' => false,
        // Default to false
        'allowSelfSigned' => true,
        // Default to null
        'caFile' => '/path/to/ca.file',
        // Default to null
        'ciphers' => 'TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256',
    ],
]);
```

### HTTP transport

```php
$transport = new yiier\graylog\transport\HttpTransport([
    // Host name or IP. Default to 127.0.0.1
    'host' => 'graylog.example.org',
    // HTTP port. Default to 12202
    'port' => 12202,
    // Query path. Default to /gelf
    'path' => '/my/custom/greylog',
    // SSL options. (optional)
    'sslOptions' => [
        // Default to true
        'verifyPeer' => false,
        // Default to false
        'allowSelfSigned' => true,
        // Default to null
        'caFile' => '/path/to/ca.file',
        // Default to null
        'ciphers' => 'TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256',
    ],
]);
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

### Use `\yiier\graylog\Log::info`

```php
<?php
\yiier\graylog\Log::info(
    'Test short message',
    'Test full message'
);


\yiier\graylog\Log::info(
    'Test short message',
    'Test full message', 
    [
        'additional1' => 'abc',
        'additional2' => 'def',
    ],
    'graylog'
);
```

### Use `Yii::info`

```php
<?php
// short_message will contain string representation of ['test1' => 123, 'test2' => 456],
// no full_message will be sent
Yii::info([
    'test1' => 123,
    'test2' => 456,
]);

// short_message will contain 'Test short message',
// two additional fields will be sent,
// full_message will contain all other stuff without 'short' and 'additional':
// string representation of ['test1' => 123, 'test2' => 456]
Yii::info([
    'test1' => 123,
    'test2' => 456,
    'short' => 'Test short message',
    'additional' => [
        'additional1' => 'abc',
        'additional2' => 'def',
    ],
]);
 
// short_message will contain 'Test short message',
// two additional fields will be sent,
// full_message will contain 'Test full message', all other stuff will be lost
Yii::info([
    'test1' => 123,
    'test2' => 456,
    'short' => 'Test short message',
    'full' => 'Test full message',
    'additional' => [
        'additional1' => 'abc',
        'additional2' => 'def',
    ],
]);
```

## Reference

- [alexeevdv/yii2-graylog-target](https://github.com/alexeevdv/yii2-graylog-target)
- [RomeroMsk/yii2-graylog2](https://github.com/RomeroMsk/yii2-graylog2)
