<?php

namespace App\Action\Main;

use Mfw\Exception\CoreException;

/**
 * Class Error
 * @package App\Controller
 */
class Error
{
    /**
     * @throws CoreException
     */
    public function __invoke()
    {
        throw (new CoreException('Demo exception'))
            ->setContext(['context' => 'value']);
    }
}
