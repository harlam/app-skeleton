<?php

namespace App\Exception;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Throwable;
use Whoops\Exception\Inspector;
use Whoops\RunInterface;

/**
 * Class BaseHandler
 * @package App\Exception
 */
final class BaseHandler
{
    private $emitter;

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param Throwable $throwable
     * @param Inspector $inspector
     * @param RunInterface $run
     */
    public function __invoke(Throwable $throwable, Inspector $inspector, RunInterface $run)
    {
        $response = new EmptyResponse(500);

        switch (get_class($throwable)) {
            case HttpException::class:
                $this->emitter->emit($response->withStatus($throwable->getCode(), $throwable->getMessage()));
                break;
            default:
                $this->emitter->emit($response);
        }
    }
}