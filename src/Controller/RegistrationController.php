<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        // If user is already logged in, redirect to home
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Check if email already exists
            $email = $form->get('email')->getData();
            if ($userRepository->findOneBy(['email' => $email])) {
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already registered.'));
            }

            // Validate password confirmation
            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($plainPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new \Symfony\Component\Form\FormError('The passwords do not match'));
            }

            if ($form->isValid()) {
                // Hash the password
                $hashedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                );
                
                // Set the hashed password
                $user->setPassword($hashedPassword);
                
                // Save the user
                $entityManager->persist($user);
                $entityManager->flush();

                // Add a success flash message
                $this->addFlash('success', 'Your account has been created successfully! You can now log in.');

                // Redirect to login page
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
} 