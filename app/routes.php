<?php

/***************************************************************************************
 * Route format:
 *
 * - 'PagesController::home' runs 'App\Controllers\PagesController->home($vars)' method,
 * - 'getHome' runs 'App\getHome($vars)' function.
 *
 * @var FastRoute\RouteCollector $r
 */

$r->addRoute('GET', '/', 'PagesController::home');
