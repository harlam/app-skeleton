<?php

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Pimple\Psr11\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/../bootstrap.php';

/** @var Container $container */
$container = require_once __APP__ . '/src/container.php';

/** @var ServerRequestInterface $request */
$request = $container->get(ServerRequestInterface::class);

/** @var RequestHandlerInterface $handler */
$handler = $container->get(RequestHandlerInterface::class);

/** @var \Psr\Http\Message\ResponseInterface $response */
$response = $handler->handle($request);

/** @var EmitterInterface $emitter */
$emitter = $container->get(EmitterInterface::class);

$emitter->emit($response);