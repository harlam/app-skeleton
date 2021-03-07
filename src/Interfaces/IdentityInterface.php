<?php

namespace App\Interfaces;

/**
 * Interface IdentityInterface
 * @package App\Interfaces
 */
interface IdentityInterface
{
    /**
     * @param UserInterface $user
     */
    public function init(UserInterface $user): void;

    /**
     * @return UserInterface|null
     */
    public function getIdentity(): ?UserInterface;
}
