<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post as PostOperation;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'category:item:get'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'category:collection:get'],
        ),
        new PostOperation(
            normalizationContext: ['groups' => 'category:post:read'],
            denormalizationContext: ['groups' => 'category:post'],
        )
    ]
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["category:collection:get","category:item:get", "post:item:get", "category:post:read", "post:collection:get"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["category:collection:get", "category:item:get", "post:item:get", "category:post", "post:collection:get"])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
