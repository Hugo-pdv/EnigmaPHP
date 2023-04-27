<?php

namespace App\Customers\Infrastructure\Symfony\Controller;

use App\Customers\Application\Message\FindUserQuery;
use App\Customers\Application\Message\UserRegistration;
use App\Customers\Application\Message\VerifyUserEmail;
use App\Customers\Infrastructure\Symfony\Model\User;
use App\Customers\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Customers\Infrastructure\Symfony\Form\RegistrationFormType;
use App\Customers\Infrastructure\Symfony\Security\EmailVerifier;
use App\Customers\Infrastructure\Symfony\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Tests\Handler\HandleDescriptorTest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{


    public function __construct(private  EmailVerifier $emailVerifier, private  MessageBusInterface $messageBus)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User(
                $form->get('email')->getData(),
                $form->get('displayName')->getData()
            );


            $enveloppe = $this->messageBus->dispatch(new UserRegistration($user, $form));
            $lastStamp = $enveloppe->last(HandledStamp::class);
            $user = $lastStamp->getResult();

            // TODO bus query get user


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $enveloppe=$this->messageBus->dispatch(new FindUserQuery($id));
        $lastStamp = $enveloppe->last(HandledStamp::class);
        $user = $lastStamp->getResult();

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }


        // @TODO Change the redirect on success and handle or remove the flash message in your templates

        $enveloppe=$this->messageBus->dispatch(new VerifyUserEmail($request, $user));
        $lastStamp = $enveloppe->last(HandledStamp::class);
        $isValid = $lastStamp->getResult();

        if(true !== $isValid)
        {
            $this->addFlash('verify_email_error', $translator->trans($isValid->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_homepage');
    }
}
