<?php

namespace App\Controller;

use App\Exception\AppException;

class Error
{
    public function __invoke()
    {
        throw new AppException('Demo');
    }
}