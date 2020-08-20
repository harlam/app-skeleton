<?php

namespace App\Exception;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Throwable;
use Whoops\Exception\Inspector;
use Whoops\RunInterface;

/**
 * Class Handler
 * @package App\Exception
 */
final class Handler
{
    private $emitter;

    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    public function __invoke(Throwable $throwable, Inspector $inspector, RunInterface $run)
    {
        $response = new EmptyResponse();

        switch (get_class($throwable)) {
            case HttpException::class:
                $this->emitter->emit($response->withStatus($throwable->getCode()));
                break;
            default:
                $this->emitter->emit($response->withStatus(500));
        }
    }
}