<?php

namespace App\Interfaces;

use Laminas\Diactoros\Response\JsonResponse;

/**
 * Interface ResponseFactoryInterface
 * @package App\Interfaces
 */
interface ResponseFactoryInterface
{
    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse;

    /**
     * @param JsonResponse $response
     * @return ResponseFactoryInterface
     */
    public function setResponse(JsonResponse $response): ResponseFactoryInterface;
}
