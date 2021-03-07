<?php

namespace App\Entity;

use App\Interfaces\UserInterface;

/**
 * Class FakeIdentity
 * @package App\Entity
 */
final class FakeIdentity implements UserInterface
{
    protected $username;
    protected $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
