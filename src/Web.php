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

/**
 * Class App
 * @package App
 */
class Web implements RequestHandlerInterface
{
    protected $container;
    protected $dispatcher;

    /**
     * @param Container $container
     * @param Dispatcher $dispatcher
     */
    public function __construct(Container $container, Dispatcher $dispatcher)
    {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AppException
     * @throws HttpException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                /** @var callable|string $handler */
                $handler = $routeInfo[1];

                if (is_string($handler)) {
                    if ($this->container->has($handler)) {
                        $handler = $this->container->get($handler);
                    } else {
                        $handler = $this->buildHandlerReflection($handler);
                    }
                }

                if (is_callable($handler)) {
                    return call_user_func_array($handler, [
                        'request' => $request,
                        'vars' => $routeInfo[2],
                    ]);
                }

                throw new AppException('Bad route handler');
            case Dispatcher::NOT_FOUND:
                throw new HttpException('Not found', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpException('Not allowed', 405);
            default:
                throw new AppException("Unknown action with code '{$routeInfo[0]}'");
        }
    }

    /**
     * @param string $handler
     * @return object
     * @throws AppException
     */
    protected function buildHandlerReflection(string $handler)
    {
        $reflection = new ReflectionClass($handler);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $constructorParams = [];
        /** @var \ReflectionParameter $param */
        foreach ($constructor->getParameters() as $param) {
            $class = $param->getClass();

            if ($class === null) {
                throw new AppException('Type is required');
            }

            $constructorParams[] = $this->container->get($class->getName());
        }

        return $reflection->newInstanceArgs($constructorParams);
    }
}