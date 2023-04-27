<?php

namespace App\Customers\Application\Message;

class FindUserQuery
{
    public function __construct(public readonly int $id)
    {
    }
}