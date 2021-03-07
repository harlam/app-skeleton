<?php

use DI\Resolver\Resolver;
use FastRoute\Dispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Mfw\RequestHandler;
use Pimple\Psr11\Container;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\RunInterface;

require_once __DIR__ . '/../bootstrap.php';

/** @var Container $container */
$container = require __APP__ . '/src/container.php';

/** @var RunInterface $whoops */
$whoops = $container->get(RunInterface::class);
$whoops->register();

/** @var Dispatcher $dispatcher */
$dispatcher = require __APP__ . '/src/routes.php';
$middlewares = require __APP__ . '/src/middlewares.php';

/** @var ServerRequestInterface $request */
$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$resolver = new Resolver($container);
$handler = new RequestHandler($dispatcher, $resolver, $middlewares);

$response = $handler->handle($request);

(new SapiEmitter())
    ->emit($response);
