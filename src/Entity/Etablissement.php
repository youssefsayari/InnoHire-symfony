<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_etablissement", type: "integer")]
    private ?int $id_etablissement = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?int $code_etablissement = null;

    #[ORM\Column(length: 255)]
    private ?string $type_etablissement = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;


    


    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $utilisateur;

    public function getId(): ?int
    {
        return $this->id_etablissement;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCodeEtablissement(): ?int
    {
        return $this->code_etablissement;
    }

    public function setCodeEtablissement(int $code_etablissement): static
    {
        $this->code_etablissement = $code_etablissement;

        return $this;
    }

    public function getTypeEtablissement(): ?string
    {
        return $this->type_etablissement;
    }

    public function setTypeEtablissement(string $type_etablissement): static
    {
        $this->type_etablissement = $type_etablissement;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

  



}
