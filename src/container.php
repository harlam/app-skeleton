<?php

use App\App;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Psr\Http\Server\RequestHandlerInterface;

$container = new Container();

/**
 * @return \Doctrine\DBAL\Driver\Connection
 */
$container[\Doctrine\DBAL\Driver\Connection::class] = function () {
    return \Doctrine\DBAL\DriverManager::getConnection(['url' => $_ENV['DBAL_CONNECTION_URL']]);
};

/**
 * Request's factory
 * @return \Psr\Http\Message\ServerRequestInterface
 */
$container[\Psr\Http\Message\ServerRequestInterface::class] = function () {
    return \Laminas\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST);
};

/**
 * Request handler
 * @return RequestHandlerInterface Обработчик http-request
 */
$container[RequestHandlerInterface::class] = function () {
    $dispatcher = require_once __APP__ . '/src/routes.php';
    return new App($dispatcher);
};

/**
 * @return \Laminas\HttpHandlerRunner\Emitter\SapiEmitter
 */
$container[\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class] = function () {
    return new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
};

return new Psr11Container($container);