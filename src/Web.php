<?php

namespace App;

use App\Exception\AppException;
use App\Exception\HttpException;
use FastRoute\Dispatcher;
use Pimple\Psr11\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use ReflectionException;
use Relay\Relay;

/**
 * Class App
 * @package App
 */
class Web implements RequestHandlerInterface
{
    protected $container;
    protected $dispatcher;
    protected $resolver;

    /**
     * @param Container $container
     * @param Dispatcher $dispatcher
     * @param ResolverInterface $resolver
     */
    public function __construct(Container $container, Dispatcher $dispatcher, ResolverInterface $resolver)
    {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AppException
     * @throws HttpException
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                foreach ($routeInfo[2] as $param => $value) {
                    $request = $request->withAttribute($param, $value);
                }

                /** @var array $handler */
                $handler = $routeInfo[1];

                $chain = $handler[1];
                $chain[] = $handler[0];

                $relay = new Relay($chain, function (string $entry) {
                    return $this->resolver->resolve($entry);
                });

                return $relay->handle($request);

//                $action = $handler[0];
//                if (is_string($action)) {
//                    $action = $this->resolver->resolve($action);
//                }
//
//                if (is_callable($action)) {
//                    return call_user_func_array($action, [
//                        'request' => $request,
//                        'vars' => $routeInfo[2],
//                    ]);
//                }

//                throw new AppException('Bad route handler');
            case Dispatcher::NOT_FOUND:
                throw new HttpException('Not found', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpException('Not allowed', 405);
            default:
                throw new AppException("Unknown action with code '{$routeInfo[0]}'");
        }
    }

    protected function processMiddleware()
    {

    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AppException
     * @throws HttpException
     * @throws ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

    }
}