<?php

namespace App\Exception;

use Psr\Log\LoggerInterface;
use Throwable;
use Whoops\Exception\Inspector;
use Whoops\RunInterface;

/**
 * Class LoggerHandler
 * @package App\Exception
 */
final class LoggerHandler
{
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Throwable $throwable
     * @param Inspector $inspector
     * @param RunInterface $run
     */
    public function __invoke(Throwable $throwable, Inspector $inspector, RunInterface $run)
    {
        if ($throwable instanceof AppException) {
            $level = $throwable->getLevel();
            $context = $throwable->getContext();
        } else {
            $level = 'warning';
            $context = [];
        }

        $this->logger->log($level, $throwable->getMessage(), $context);
    }
}