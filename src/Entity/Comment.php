<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $user = null;

    #[ORM\ManyToOne(targetEntity:Painting::class, inversedBy:"comment")]
    #[ORM\JoinColumn(name: "comment_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $painting;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $validate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get the value of painting
     */ 
    public function getPainting():?Painting
    {
        return $this->painting;
    }

    /**
     * Set the value of painting
     *
     * @return  self
     */ 
    public function setPainting($painting)
    {
        $this->painting = $painting;

        return $this;
    }

    /**
     * Get the value of validate
     */ 
    public function getValidate(): ? bool
    {
        return $this->validate;
    }

    /**
     * Set the value of validate
     *
     * @return  self
     */ 
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }
}
