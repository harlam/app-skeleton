<?php

use Pimple\Psr11\Container;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../bootstrap.php';

/** @var Container $container */
$container = require_once __APP__ . '/src/container.php';

/** @var \FastRoute\Dispatcher $dispatcher */
$dispatcher = require_once __APP__ . '/src/routes.php';

/** @var ServerRequestInterface $request */
$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST);

/** @var \Psr\Http\Message\ResponseInterface $response */
$response = (new \App\App($container, $dispatcher))
    ->handle($request);

(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter)
    ->emit($response);