<?php

namespace App\Service;

use App\Interfaces\IdentityInterface;
use App\Interfaces\UserInterface;
use Exception;

class Identity implements IdentityInterface
{
    protected $identity;

    /**
     * @param UserInterface $user
     * @throws Exception
     */
    public function init(UserInterface $user): void
    {
        if ($this->identity !== null) {
            throw new Exception('Already initialized');
        }

        $this->identity = $user;
    }

    /**
     * @return UserInterface|null
     */
    public function getIdentity(): ?UserInterface
    {
        return $this->identity;
    }
}
