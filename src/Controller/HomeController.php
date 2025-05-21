<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        // Get latest posts
        $latestPosts = $postRepository->findBy([], ['createdAt' => 'DESC'], 6);
        
        // Get all categories
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'latestPosts' => $latestPosts,
            'categories' => $categories,
        ]);
    }
}
