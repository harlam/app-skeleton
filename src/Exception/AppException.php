<?php

namespace App\Exception;

use Exception;

/**
 * Class AppException
 * @package App\Exception
 */
class AppException extends Exception
{
    public const DEBUG = 'debug';

    public const INFO = 'info';

    public const NOTICE = 'notice';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    public const CRITICAL = 'critical';

    public const ALERT = 'alert';

    public const EMERGENCY = 'emergency';

    /** @var string */
    protected $level = self::WARNING;
    /** @var array */
    protected $context = [];

    /**
     * @param string $level
     * @return AppException
     */
    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param array $context
     * @return AppException
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}