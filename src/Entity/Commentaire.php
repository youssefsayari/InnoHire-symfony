<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $id_publication = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $id_utilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $description_co = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_co = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPublication(): ?Post
    {
        return $this->id_publication;
    }

    public function setIdPublication(?Post $id_publication): static
    {
        $this->id_publication = $id_publication;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $id_utilisateur): static
    {
        $this->id_utilisateur = $id_utilisateur;

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
