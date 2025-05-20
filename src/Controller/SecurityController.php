<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('security/register.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
}
