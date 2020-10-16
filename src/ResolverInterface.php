<?php

namespace App;

interface ResolverInterface
{
    /**
     * @param string $value
     * @return mixed
     */
    public function resolve(string $value);
}