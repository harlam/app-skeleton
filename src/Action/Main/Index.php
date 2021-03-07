<?php

namespace App\Action\Main;

use App\Interfaces\IdentityInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Index
 * @package App\Action\Main
 */
class Index
{
    protected $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        $username = $this->identity->getIdentity() === null ? 'no identity' : $this->identity->getIdentity()->getUsername();

        return new JsonResponse([
            'identity-username' => $username,
            'attributes' => $request->getAttributes(),
            'demo-header' => $request->getHeaderLine('X-Demo')
        ]);
    }
}
