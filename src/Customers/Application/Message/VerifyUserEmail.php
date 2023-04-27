<?php

namespace App\Customers\Application\Message;

use App\Customers\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class VerifyUserEmail
{
    public function __construct(public readonly Request $request,public readonly User $user)
    {

    }
}