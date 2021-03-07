<?php

namespace App\Middleware;

use App\Entity\FakeIdentity;
use App\Interfaces\IdentityInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class FakeAuthMiddleware
 * @package App\Middleware
 */
class FakeAuthMiddleware implements MiddlewareInterface
{
    protected $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->identity->init(new FakeIdentity('demo', 'secret'));

        return $handler->handle($request->withHeader('X-Demo', 'Demo-Value'));
    }
}
