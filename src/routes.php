<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$routes = [
    'middleware' => [
        'before' => [
            \App\Middleware\FirstMiddleware::class
        ],
        'after' => [
            \App\Middleware\LastMiddleware::class
        ],
    ],
    'route' => [
        [['get', 'post'], '/', \App\Action\Main\Index::class, [\App\Middleware\SecondMiddleware::class]],
        '/group' => [
            [['get', 'post'], '/action1', \App\Action\Main\Index::class, 'middleware' => []],
            [['get', 'post'], '/action2', \App\Action\Main\Index::class, 'middleware' => []],
        ],
    ],
];

return simpleDispatcher(function (RouteCollector $router) {
    $router->addGroup('/primary', function (RouteCollector $primary) {
        $primary->get('/main', \App\Action\Main\Index::class, [\App\Middleware\LastMiddleware::class]);
        $primary->get('/second', \App\Action\Main\Index::class, [\App\Middleware\LastMiddleware::class]);
    }, [\App\Middleware\FirstMiddleware::class, \App\Middleware\TestMiddleware::class]);

    $router->get('/', [\App\Action\Main\Index::class, [
        \App\Middleware\TestMiddleware::class
    ]]);
});
