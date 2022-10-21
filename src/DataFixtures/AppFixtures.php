<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $categories = [
            'Actualités',
            'Sport',
            'Technologie'
        ];

        foreach ($categories as $cat) {
            $categorie = new Category();
            $categorie->setName($cat);
            $manager->persist($categorie);
        }

        $manager->flush();

        $lorem = "orem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        for ($i=0 ; $i < 30; $i++) {
            $post = new Post();
            $post->setTitle('Article #'.$i);
            $post->setContent($lorem);
            $category = $manager->getRepository(Category::class)->findOneBy([]);
            $post->setCategory($category);
            $manager->persist($post);
        }

        $manager->flush();
    }
}
