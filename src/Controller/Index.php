<?php

namespace App\Controller;

use Laminas\Diactoros\Response\JsonResponse;

class Index
{
    public function __invoke()
    {
        return new JsonResponse(['index']);
    }
}