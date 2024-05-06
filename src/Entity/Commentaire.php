<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_commentaire", type: "integer")]
    private ?int $id_commentaire = null;

    #[Assert\NotNull(message: "Le champ post ne peut pas être vide.")]
    #[ORM\ManyToOne(targetEntity: "Post", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "id_publication", nullable: false, referencedColumnName: "id_post")]
    private ?Post $post;

    #[Assert\NotNull(message: "Le champ utilisateur ne peut pas être vide.")]
    #[ORM\ManyToOne(targetEntity: "Utilisateur")]
    #[ORM\JoinColumn(name: "id_utilisateur", nullable: false, referencedColumnName: "id_utilisateur")]
    private ?Utilisateur $utilisateur;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ description_co ne peut pas être vide.")]
    private ?string $description_co = null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "Le champ date_co ne peut pas être vide.")]
    private ?\DateTimeInterface $date_co = null;
public function getIdCommentaire(): ?int
    {
        return $this->id_commentaire;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDescriptionCo(): ?string
    {
        return $this->description_co;
    }

    public function setDescriptionCo(string $description_co): static
    {
        $this->description_co = $description_co;

        return $this;
    }

    public function getDateCo(): ?\DateTimeInterface
    {
        return $this->date_co;
    }

    public function setDateCo(\DateTimeInterface $date_co): static
    {
        $this->date_co = $date_co;

        return $this;
    }
}