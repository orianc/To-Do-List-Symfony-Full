<?php

namespace App\Entity;

use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\TodoRepository;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide !")
     * @Assert\Length(min=4, minMessage = "Au minimum {{ limit }} caractères")
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide !")
     * 
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_for;
    
    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="todos")
     */
    private $category;
    
    /**
     * Lors de l'instance de l'objet on défni les dates de création et de maj
     */
    public function __construct()
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this
            ->setCreatedAt($now)
            ->setUpdateAt($now);    
    }
    
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

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getDateFor(): ?\DateTimeInterface
    {
        return $this->date_for;
    }

    public function setDateFor(?\DateTimeInterface $date_for): self
    {
        $this->date_for = $date_for;

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
