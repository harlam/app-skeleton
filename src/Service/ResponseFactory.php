<?php

namespace App\Service;

use App\Interfaces\ResponseFactoryInterface;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class ResponseFactory
 * @package App\Service
 */
class ResponseFactory implements ResponseFactoryInterface
{
    protected $response;

    public function __construct()
    {
        $this->response = new JsonResponse([]);
    }

    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        return $this->response;
    }

    /**
     * @param JsonResponse $response
     * @return ResponseFactoryInterface
     */
    public function setResponse(JsonResponse $response): ResponseFactoryInterface
    {
        $this->response = $response;
        return $this;
    }
}
