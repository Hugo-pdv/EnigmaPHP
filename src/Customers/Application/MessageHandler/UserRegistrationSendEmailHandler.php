<?php

namespace App\Customers\Application\MessageHandler;

use App\Customers\Application\Message\UserRegistration;
use App\Customers\Infrastructure\Symfony\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler(priority: 16)]
final class UserRegistrationSendEmailHandler
{
    public function __construct(private EmailVerifier $emailVerifier)
    {

    }
    public function __invoke(UserRegistration $message)
    {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $message->user,
            (new TemplatedEmail())
                ->from(new Address('registration@moussaka.local', 'moussaka'))
                ->to($message->user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $message->user;
    }
}
