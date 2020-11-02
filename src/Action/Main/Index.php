<?php

namespace App\Action\Main;

use App\Service\Service;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class Index
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return new JsonResponse([$this->service->index(), 'attributes' => $request->getAttributes()]);
    }
}