<?php

namespace App\Customers\Infrastructure\Symfony\Model;

class User
{
    public function __construct(
        public string $email,
        public string $displayName
    ) {}
}