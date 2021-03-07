<?php

namespace App\Interfaces;

/**
 * Interface UserInterface
 * @package App\Interfaces
 */
interface UserInterface
{
    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;
}
