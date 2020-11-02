<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $router) {
    $router->get('/', [
        \App\Action\Main\Index::class
    ]);
});
