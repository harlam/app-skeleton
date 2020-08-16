<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $router) {
    $router->addRoute('GET', '/', new \App\Controller\Index());
});
