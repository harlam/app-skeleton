<?php

use App\ServiceProvider\AppServiceProvider;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container = new Container();

(new AppServiceProvider)->register($container);

return new Psr11Container($container);
