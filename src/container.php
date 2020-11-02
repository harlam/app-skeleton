<?php

use App\Exception\ExceptionHandler;
use App\Service\Service;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Psr\Log\LoggerInterface;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

$container = new Container();

/**
 * @param Container $container
 * @return RunInterface
 */
$container[RunInterface::class] = function (Container $container) {
    $whoops = new Whoops\Run();

    if ($_ENV['APP_ENVIRONMENT'] === 'development') {
        /** @var HandlerInterface $handler Show pretty in development mode */
        return $whoops->pushHandler(new PrettyPageHandler());
    }

    /** @var LoggerInterface $logger Log all exceptions */
    $logger = $container[LoggerInterface::class];

    $handler = new CallbackHandler(new ExceptionHandler($logger));

    return $whoops->pushHandler($handler);
};

/**
 * @return LoggerInterface
 */
$container[LoggerInterface::class] = function () {
    $level = $_ENV['APP_LOG_LEVEL'] === null ? 'info' : $_ENV['APP_LOG_LEVEL'];
    return (new Logger('app'))
        ->pushHandler(new StreamHandler(__APP__ . '/runtime/app.log', $level))
        ->pushProcessor(new WebProcessor());
};

/**
 * @return EntityManager
 */
$container[EntityManager::class] = function () {
    $isDevMode = $_ENV['ORM_DEVELOPMENT_MODE'] === 'true' ? true : false;
    $config = Setup::createAnnotationMetadataConfiguration(['src/Entity'], $isDevMode);

    return EntityManager::create(['url' => $_ENV['DBAL_CONNECTION_URL']], $config);
};

/**
 * Service example
 * @return Service
 */
$container[Service::class] = function () {
    return new Service();
};

return new Psr11Container($container);