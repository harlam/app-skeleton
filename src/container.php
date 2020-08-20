<?php

use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container = new Container();

/**
 * @param Container $container
 * @return \Whoops\RunInterface
 */
$container[\Whoops\RunInterface::class] = function (Container $container) {
    if ($_ENV['APP_ENVIRONMENT'] === 'development') {
        /** @var \Whoops\Handler\HandlerInterface $handler Show pretty in development mode */
        $handler = new \Whoops\Handler\PrettyPageHandler();
    } else {
        /** @var \Laminas\HttpHandlerRunner\Emitter\EmitterInterface $emitter */
        $emitter = $container[\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class];
        /** @var \Whoops\Handler\HandlerInterface $handler Show minimal details in production */
        $handler = new \Whoops\Handler\CallbackHandler(new \App\Exception\BaseHandler($emitter));
    }

    /** @var \Psr\Log\LoggerInterface $logger Log all exceptions always */
    $logger = $container[\Psr\Log\LoggerInterface::class];
    $loggerHandler = new \Whoops\Handler\CallbackHandler(new \App\Exception\LoggerHandler($logger));

    return (new Whoops\Run())
        ->pushHandler($handler)
        ->pushHandler($loggerHandler);
};

/**
 * @return \Monolog\Logger
 */
$container[\Psr\Log\LoggerInterface::class] = function () {
    $level = $_ENV['APP_LOG_LEVEL'] === null ? 'info' : $_ENV['APP_LOG_LEVEL'];
    return (new \Monolog\Logger('app'))
        ->pushHandler(new \Monolog\Handler\StreamHandler(__APP__ . '/runtime/app.log', $level))
        ->pushProcessor(new \Monolog\Processor\WebProcessor());
};

/**
 * @return \Laminas\HttpHandlerRunner\Emitter\SapiEmitter
 */
$container[\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class] = function () {
    return new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
};

/**
 * @return \Doctrine\ORM\EntityManager
 */
$container[\Doctrine\ORM\EntityManager::class] = function () {
    $isDevMode = $_ENV['ORM_DEVELOPMENT_MODE'] === 'true' ? true : false;
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(['src/Entity'], $isDevMode);

    return \Doctrine\ORM\EntityManager::create(['url' => $_ENV['DBAL_CONNECTION_URL']], $config);
};

/**
 * Service example
 * @return \App\Service\Service
 */
$container[\App\Service\Service::class] = function () {
    return new \App\Service\Service();
};

return new Psr11Container($container);