<?php

namespace App\Action\Main;

use App\Interfaces\IdentityInterface;
use App\Interfaces\ResponseFactoryInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Index
 * @package App\Action\Main
 */
class Index
{
    protected $identity;
    protected $responseFactory;

    public function __construct(IdentityInterface $identity, ResponseFactoryInterface $responseFactory)
    {
        $this->identity = $identity;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        $username = $this->identity->getIdentity() === null ? 'no identity' : $this->identity->getIdentity()->getUsername();

        return $this->responseFactory->getResponse()->withPayload([
            'identity-username' => $username,
            'attributes' => $request->getAttributes(),
            'demo-header' => $request->getHeaderLine('X-Demo')
        ]);
    }
}
