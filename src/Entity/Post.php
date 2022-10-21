<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

use App\Controller\Api\CustomSearchController;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'post:item:get'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'post:collection:get'],
        ),
        new Get(
            name: 'custom_search',
            uriTemplate: '/posts/search',
            controller: CustomSearchController::class
        )
    ],
    normalizationContext: ['groups' => 'post:read'],
    denormalizationContext: ['groups' => 'post:write'],
    paginationItemsPerPage: 10,
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'exact', 'content' => 'partial'])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["post:item:get", "post:collection:get"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["post:item:get", "post:collection:get"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["post:item:get", "post:collection:get"])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[Groups(["post:item:get", "post:collection:get"])]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
