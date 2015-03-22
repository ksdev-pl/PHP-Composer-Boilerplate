<?php

/********************************************************************
 * Configure all the things!
 */

error_reporting(E_ALL);

date_default_timezone_set('UTC');

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';

Dotenv::load(ROOT);

$logger = App\Logger::getInstance();
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

        $r->addRoute('GET', '/', 'getIndex');

    }, ['cacheFile' => ROOT . '/storage/cache/routes']
);

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handler($vars);
        break;
}

/********************************************************************
 * Code all the things!
 */

function getIndex() {
    header('Content-Type: text/html; charset=utf-8');

    echo '<pre>
─────────────────────────────▄██▄
─────────────────────────────▀███
────────────────────────────────█
───────────────▄▄▄▄▄────────────█
──────────────▀▄────▀▄──────────█
──────────▄▀▀▀▄─█▄▄▄▄█▄▄─▄▀▀▀▄──█
─────────█──▄──█────────█───▄─█─█
─────────▀▄───▄▀────────▀▄───▄▀─█
──────────█▀▀▀────────────▀▀▀─█─█
──────────█───────────────────█─█
▄▀▄▄▀▄────█──▄█▀█▀█▀█▀█▀█▄────█─█
█▒▒▒▒█────█──█████████████▄───█─█
█▒▒▒▒█────█──██████████████▄──█─█
█▒▒▒▒█────█───██████████████▄─█─█
█▒▒▒▒█────█────██████████████─█─█
█▒▒▒▒█────█───██████████████▀─█─█
█▒▒▒▒█───██───██████████████──█─█
▀████▀──██▀█──█████████████▀──█▄█
──██───██──▀█──█▄█▄█▄█▄█▄█▀──▄█▀
──██──██────▀█─────────────▄▀▓█
──██─██──────▀█▀▄▄▄▄▄▄▄▄▄▀▀▓▓▓█
──████────────█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──███─────────█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──██──────────█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──██──────────█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──██─────────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──██────────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█
──██───────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█▌
──██──────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█▌
──██─────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█▌
──██────▐█▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓█▌
    </pre>';
}
