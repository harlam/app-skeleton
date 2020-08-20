<?php

use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container = new Container();

/**
 * @return \Doctrine\ORM\EntityManager
 */
$container[\Doctrine\ORM\EntityManager::class] = function () {
    $isDevMode = $_ENV['ORM_DEVELOPMENT_MODE'] === 'true' ? true : false;
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(['src/Entity'], $isDevMode);

    return \Doctrine\ORM\EntityManager::create(['url' => $_ENV['DBAL_CONNECTION_URL']], $config);
};

/**
 * @param Container $container
 * @return \Whoops\Handler\HandlerInterface
 */
$container[\Whoops\Handler\HandlerInterface::class] = function (Container $container) {
    if ($_ENV['APP_ENVIRONMENT'] === 'development') {
        $handler = new \Whoops\Handler\PrettyPageHandler();
    } else {
        /** @var \Laminas\HttpHandlerRunner\Emitter\EmitterInterface $emitter */
        $emitter = $container[\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class];
        $handler = new \Whoops\Handler\CallbackHandler(new \App\Exception\Handler($emitter));
    }
    return $handler;
};

/**
 * @return \Laminas\HttpHandlerRunner\Emitter\SapiEmitter
 */
$container[\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class] = function () {
    return new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
};

/**
 * Service example
 * @return \App\Service\Service
 */
$container[\App\Service\Service::class] = function () {
    return new \App\Service\Service();
};

return new Psr11Container($container);