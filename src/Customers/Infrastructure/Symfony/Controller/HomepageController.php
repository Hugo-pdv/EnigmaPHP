<?php

namespace App\Customers\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->render('homepage/index.html.twig', [
                'username' => $this->getUser()->getDisplayName(),
            ]);
        }
        return $this->render('homepage/index.html.twig', []);
    }
}
