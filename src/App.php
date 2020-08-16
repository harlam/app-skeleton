<?php

namespace App;

use App\Exception\RequestHandleException;
use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
{
    protected $dispatcher;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws RequestHandleException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                /** @var callable $callable */
                $callable = $routeInfo[1];
                return call_user_func_array($callable, [
                    'request' => $request,
                    'vars' => $routeInfo[2],
                ]);
            case Dispatcher::NOT_FOUND:
                throw new RequestHandleException('Not found', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new RequestHandleException('Method not allowed', 405);
            default:
                throw new RequestHandleException("Unknown action with code '{$routeInfo[0]}'");
        }
    }
}