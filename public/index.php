<?php

use App\Web;
use Laminas\Diactoros\ServerRequestFactory;
use Pimple\Psr11\Container;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../bootstrap.php';

/** @var Container $container */
$container = require_once __APP__ . '/src/container.php';

(new Whoops\Run)
    ->pushHandler($container->get(\Whoops\Handler\HandlerInterface::class))
    ->register();

/** @var \FastRoute\Dispatcher $dispatcher */
$dispatcher = require_once __APP__ . '/src/routes.php';

/** @var ServerRequestInterface $request */
$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

/** @var \Psr\Http\Message\ResponseInterface $response */
$response = (new Web($container, $dispatcher))
    ->handle($request);

/** @var \Laminas\HttpHandlerRunner\Emitter\EmitterInterface $emitter */
$emitter = $container->get(\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class);

$emitter
    ->emit($response);