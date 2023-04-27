<?php

namespace App\Customers\Application\MessageHandler;

use App\Customers\Application\Message\UserRegistration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(priority: 32)]
final class StoreUserHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function __invoke(UserRegistration $userRegistration)
    {
        $this->entityManager->persist($userRegistration->user);
        $this->entityManager->flush();
    }
}