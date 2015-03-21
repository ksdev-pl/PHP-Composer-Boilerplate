<?php namespace App;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    private static $instance;

    private function __construct() {}

    /**
     * Get the instance of the Monolog Logger
     *
     * @return \Monolog\Logger
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new MonologLogger('app');
            self::$instance->pushHandler(new StreamHandler(ROOT . '/storage/logs/app.log'));
        }

        return self::$instance;
    }
}
