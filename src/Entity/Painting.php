<?php

namespace App\Entity;

use App\Repository\PaintingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaintingRepository::class)]
class Painting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $image=NULL;

    #[ORM\Column(type:"text",length: 16383)]
    private ?string $description = null;

    #[ORM\Column(type:"text",length: 16383, nullable: true)]
    private ?string $technic = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\OneToMany(targetEntity:Comment::class, mappedBy: "painting")]
    private $comments;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTechnic(): ?string
    {
        return $this->technic;
    }

    public function setTechnic(?string $technic): self
    {
        $this->technic = $technic;

        return $this;
    }
 
    public function getImage():?string
    {
        return $this->image;
    }

    public function setImage($image):self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory():string
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category):self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of comments
     */ 
    public function getComments():?Comment
    {
        return $this->comments;
    }

    /**
     * Set the value of comments
     *
     * @return  self
     */ 
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }
}
