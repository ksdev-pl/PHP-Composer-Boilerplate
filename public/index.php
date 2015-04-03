<?php

/********************************************************************
 * Configure all the things!
 */

error_reporting(E_ALL);
date_default_timezone_set('UTC');

define('ROOT',  dirname(__DIR__));
define('APP',   ROOT . '/app/');

require_once ROOT . '/vendor/autoload.php';

Dotenv::load(ROOT);

$logger = App\Helpers\Logger::getInstance();
$whoops = new Whoops\Run;
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 'On');

    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
    $whoops->pushHandler(function ($exception) use ($logger) {
        $logger->addError((string) $exception);
    });
}
else {
    ini_set('display_errors', 'Off');

    $whoops->pushHandler(function ($exception) use ($logger) {
        $logger->addError((string) $exception);
        http_response_code(500);
        exit('A website error has occurred.
            The website administrator has been notified of the issue.
            Sorry for the temporary inconvenience.');
    });
}
$whoops->register();


/********************************************************************
 * Route all the things!
 */

$dispatcher = FastRoute\cachedDispatcher(
    function (FastRoute\RouteCollector $r) {
        require_once APP . '/routes.php';
    }, [
        'cacheFile' => ROOT . '/storage/cache/routes',
        'cacheDisabled' => getenv('APP_DEBUG') === 'true'
    ]
);

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        exit('Not Found');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        exit('Method Not Allowed');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if (strpos($handler, '::') !== false) {
            list($class, $method) = explode('::', $handler);
            $class = 'App\Controllers\\' . $class;
            $obj = new $class();
            $obj->$method($vars);
        }
        else {
            $function = 'App\\' . $handler;
            $function($vars);
        }
        break;
}
