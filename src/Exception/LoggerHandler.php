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
            $level = AppException::WARNING;
            $context = [];
        }

        $message = $inspector->getExceptionName() . ' ' . $throwable->getMessage()
            . ', File: ' . $throwable->getFile()
            . ', Line: ' . $throwable->getLine();

        $this->logger->log($level, $message, $context);
    }
}