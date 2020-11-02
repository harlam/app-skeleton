<?php

use FastRoute\Dispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Mfw\Resolver;
use Mfw\WebApp;
use Pimple\Psr11\Container;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../bootstrap.php';

/** @var Container $container */
$container = require_once __APP__ . '/src/container.php';

/** @var \Whoops\RunInterface $whoops */
$whoops = $container->get(Whoops\RunInterface::class);

$whoops->register();

/** @var Dispatcher $dispatcher */
$dispatcher = require_once __APP__ . '/src/routes.php';
$middlewares = require_once __APP__ . '/src/middlewares.php';

/** @var ServerRequestInterface $request */
$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$resolver = new Resolver($container);
$handler = new WebApp($dispatcher, $resolver, $middlewares);

$response = $handler->handle($request);

(new SapiEmitter())
    ->emit($response);