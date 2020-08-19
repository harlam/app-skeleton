<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $router) {
    $router->addRoute('GET', '/', \App\Controller\Index::class);
});
