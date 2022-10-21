<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostHandler
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function createPostFromData($data)
    {
        $post = new Post();
        $post->setTitle($data['name']);
        $this->em->persist($post);
        $this->em->flush();
    }

}
