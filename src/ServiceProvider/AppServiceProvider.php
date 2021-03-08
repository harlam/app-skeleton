<?php

namespace App\ServiceProvider;

use App\Exception\ExceptionHandler;
use App\Interfaces\IdentityInterface;
use App\Interfaces\ResponseFactoryInterface;
use App\Service\Identity;
use App\Service\ResponseFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;
use Whoops\RunInterface;

/**
 * Class AppServiceProvider
 * @package App\ServiceProvider
 */
class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     *
     * @see \App\Interfaces\IdentityInterface
     * @see \App\Interfaces\ResponseFactoryInterface
     * @see \Psr\Log\LoggerInterface
     * @see \Doctrine\ORM\EntityManager
     * @see \Whoops\RunInterface
     */
    public function register(Container $pimple)
    {
        $pimple[IdentityInterface::class] = function () {
            return new Identity();
        };

        $pimple[ResponseFactoryInterface::class] = function () {
            return new ResponseFactory();
        };

        $pimple[LoggerInterface::class] = function () {
            $level = $_ENV['APP_LOG_LEVEL'] === null ? 'info' : $_ENV['APP_LOG_LEVEL'];
            return (new Logger('app'))
                ->pushHandler(new StreamHandler(__APP__ . '/runtime/app.log', $level))
                ->pushProcessor(new WebProcessor());
        };

        $pimple[EntityManager::class] = function () {
            $isDevMode = $_ENV['ORM_DEVELOPMENT_MODE'] === 'true';
            $config = Setup::createAnnotationMetadataConfiguration(['src/Entity'], $isDevMode);

            return EntityManager::create(['url' => $_ENV['DBAL_CONNECTION_URL']], $config);
        };

        $pimple[RunInterface::class] = function (Container $container) {
            $whoops = new WhoopsRun();

            if ($_ENV['APP_ENVIRONMENT'] === 'development') {
                /** @var HandlerInterface $handler Show pretty in development mode */
                $whoops->pushHandler(new PrettyPageHandler());
            }

            /** @var LoggerInterface $logger Log all exceptions */
            $logger = $container[LoggerInterface::class];

            $handler = new CallbackHandler(new ExceptionHandler($logger));

            return $whoops->pushHandler($handler);
        };
    }
}
