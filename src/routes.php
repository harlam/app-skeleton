<?php

use Mfw\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $router) {
    $router->addGroup('/main', function (RouteCollector $auth) {
        $auth->get('/index', [\App\Action\Main\Index::class]);
    }, [\App\Middleware\DemoMiddleware::class]);

    $router->get('/', [
        \App\Action\Main\Index::class
    ]);
}, ['routeCollector' => RouteCollector::class]);
