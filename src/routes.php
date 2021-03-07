<?php

use Mfw\RouteCollector;
use function FastRoute\simpleDispatcher;
use App\Action\Main;
use App\Middleware;

return simpleDispatcher(function (RouteCollector $router) {

    $router->get('/', [Main\Index::class]);
    $router->get('/error', [Main\Error::class]);

    $router->addGroup('/secure', function (RouteCollector $auth) {
        $auth->get('/index', [Main\Index::class]);
    }, [Middleware\FakeAuthMiddleware::class]);

}, ['routeCollector' => RouteCollector::class]);
