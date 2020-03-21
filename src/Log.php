<?php

namespace yiier\graylog;

use Psr\Log\LogLevel;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

/**
 * Class Log
 * @method static void debug(array|string $short, array|string $full = '', array $additional = [], $category = 'graylog')
 * @method static void info(array|string $short, array|string $full = '', array $additional = [], $category = 'graylog')
 * @method static void warning(array|string $short, array|string $full = '', array $additional = [], $category = 'graylog')
 * @method static void error(array|string $short, array|string $full = '', array $additional = [], $category = 'graylog')
 *
 */
class Log
{
    public static function __callStatic(string $name, array $arguments)
    {
        static::writeLog($name, $arguments);
    }

    public static function writeLog(string $name, array $message)
    {
        $argOffset = 0;

        // $message['trace_id'] = request()->requestId; todo

        $message['short'] = ArrayHelper::remove($message, (string)(0 + $argOffset));
        $message['full'] = ArrayHelper::remove($message, (string)(1 + $argOffset));
        $message['additional'] = ArrayHelper::remove($message, (string)(2 + $argOffset));
        $category = ArrayHelper::remove($message, (string)(3 + $argOffset), 'graylog');

        $level = self::mapYiiLevel($name);

        \Yii::getLogger()->log($message, $level, $category);
    }


    public static function mapYiiLevel($logLevel)
    {
        return ArrayHelper::getValue([
            LogLevel::DEBUG => Logger::LEVEL_TRACE,
            LogLevel::INFO => Logger::LEVEL_INFO,
            LogLevel::WARNING => Logger::LEVEL_WARNING,
            LogLevel::ERROR => Logger::LEVEL_ERROR,
        ], $logLevel, Logger::LEVEL_INFO);
    }

}
