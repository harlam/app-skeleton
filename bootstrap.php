<?php

use Dotenv\Dotenv;

require_once 'vendor/autoload.php';

define('__APP__', __DIR__);

(Dotenv::createImmutable(__APP__))
    ->load();
