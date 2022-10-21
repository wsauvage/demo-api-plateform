<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CustomSearchController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $posts = $this->em->getRepository(Post::class)->search($query);

        return $this->json([
            "success" => true,
            "posts" => $posts
        ]);
    }
}