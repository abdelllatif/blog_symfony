<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog', methods: ['GET'])]
    public function index(
        Request $request,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ): Response {
        // Create form for new post
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        // Get all posts
        $query = $postRepository->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        // Get categories with post counts
        $categories = $categoryRepository->findAll();

        // Check if there are any posts
        $totalPosts = $postRepository->count([]);
        
        if ($totalPosts === 0) {
            // If no posts, render template with empty state
            return $this->render('blog/index.html.twig', [
                'posts' => null,
                'categories' => $categories,
                'tags' => $tagRepository->findAll(),
                'latestPosts' => [],
                'isEmpty' => true,
                'form' => $form->createView()
            ]);
        }

        // Paginate posts
        $posts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9 // posts per page
        );

        $tags = $tagRepository->findAll();
        $latestPosts = $postRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'latestPosts' => $latestPosts,
            'isEmpty' => false,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('posts_directory'),
                        $newFilename
                    );
                    $post->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error uploading image');
                    return $this->redirectToRoute('app_blog');
                }
            }

            $post->setAuthor($this->getUser());
            $post->setCreatedAt(new \DateTime());
            
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post created successfully!');
            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->getFormErrors($form)
        ], Response::HTTP_BAD_REQUEST);
    }

    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
} 