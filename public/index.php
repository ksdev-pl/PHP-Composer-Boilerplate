<?php

/************************************************
 * Configure all the things!
 */

error_reporting(E_ALL);

date_default_timezone_set('Europe/Warsaw');

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';

Dotenv::load(ROOT);

$logger = App\Logger::getInstance();
$whoops = new \Whoops\Run;
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 'On');

    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
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


/************************************************
 * Code all the things!
 */

header('Content-Type: text/html; charset=utf-8');

$klein = new \Klein\Klein();

$klein->respond('GET', '/', function () {

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

});

$klein->dispatch();
