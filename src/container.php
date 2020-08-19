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
 * Service example
 * @return \App\Service\Service
 */
$container[\App\Service\Service::class] = function () {
    return new \App\Service\Service();
};

return new Psr11Container($container);