<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_post", type: "integer")]
    private ?int $id_post = null;

    #[ORM\ManyToOne(targetEntity: "Utilisateur")]
    #[ORM\JoinColumn(name: "id_utilisateur", nullable: false, referencedColumnName: "id_utilisateur")]
    #[Assert\NotBlank(message: "Le champ utilisateur ne peut pas être vide.")]
    private ?Utilisateur $utilisateur;

    #[Assert\NotBlank(message: "Le champ audience ne peut pas être vide.")]
    #[ORM\Column(length: 255)]
    private ?string $audience = null;

    #[Assert\NotNull(message: "La date ne peut pas être vide.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: "Le champ caption ne peut pas être vide.")]
    #[ORM\Column(length: 255)]
    private ?string $caption = null;

    #[Assert\NotBlank(message: "Le champ image ne peut pas être vide.")]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Assert\Range(min: 0, minMessage: "Le nombre total de réactions doit être supérieur ou égal à zéro.")]
    #[ORM\Column(name: "totalReactions", nullable: true)]
    private ?int $totalReactions = null;

    #[Assert\Range(min: 0, minMessage: "Le nombre de commentaires doit être supérieur ou égal à zéro.")]
    #[ORM\Column(name: "nbComments", nullable: true)]
    private ?int $nbComments = null;
public function getIdPost(): ?int
    {
        return $this->id_post;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getAudience(): ?string
    {
        return $this->audience;
    }

    public function setAudience(string $audience): static
    {
        $this->audience = $audience;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTotalReactions(): ?int
    {
        return $this->totalReactions;
    }

    public function setTotalReactions(?int $totalReactions): static
    {
        $this->totalReactions = $totalReactions;

        return $this;
    }

    public function getNbComments(): ?int
    {
        return $this->nbComments;
    }

    public function setNbComments(?int $nbComments): static
    {
        $this->nbComments = $nbComments;

        return $this;
    }

}