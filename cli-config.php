<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'bootstrap.php';

$container = require_once __APP__ . '/src/container.php';

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

return ConsoleRunner::createHelperSet($entityManager);