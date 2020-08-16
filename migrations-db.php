<?php

require_once 'bootstrap.php';

$container = require_once __APP__ . '/src/container.php';

/** @var \Doctrine\DBAL\Driver\Connection $connection */
$connection = $container->get(\Doctrine\DBAL\Driver\Connection::class);

return $connection;