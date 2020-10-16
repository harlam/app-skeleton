<?php

namespace App\Action\Main;

use App\Exception\AppException;

/**
 * Class Error
 * @package App\Controller
 */
class Error
{
    public function __invoke()
    {
        throw (new AppException('Demo exception'))
            ->setLevel(AppException::WARNING)
            ->setContext(['context' => 'value']);
    }
}