<?php

namespace App\Controller;

use App\Service\Service;
use Laminas\Diactoros\Response\JsonResponse;

class Index
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        return new JsonResponse([$this->service->index()]);
    }
}