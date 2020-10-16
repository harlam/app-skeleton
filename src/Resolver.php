<?php

namespace App;

use App\Exception\AppException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Resolver implements ResolverInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $value
     * @return mixed|object
     * @throws ReflectionException
     * @throws AppException
     */
    public function resolve(string $value)
    {
        if ($this->container->has($value)) {
            return $this->container->get($value);
        }

        $reflection = new ReflectionClass($value);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $constructorParams = [];

        foreach ($constructor->getParameters() as $param) {
            $class = $param->getClass();

            if ($class === null) {
                throw new AppException('Argument type is required');
            }

            $constructorParams[] = $this->container->get($class->getName());
        }

        return $reflection->newInstanceArgs($constructorParams);
    }
}