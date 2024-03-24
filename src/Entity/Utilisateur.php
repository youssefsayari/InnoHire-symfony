<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_utilisateur", type: "integer")]
    private ?int $id_utilisateur = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $cin = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $mdp = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $role = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $otp = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;







    #[ORM\OneToMany]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;







    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getOtp(): ?int
    {
        return $this->otp;
    }

    public function setOtp(?int $otp): static
    {
        $this->otp = $otp;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }




public function getPost(): ?int
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }






//sayari chzed
    public function __toString(): string
    {
        // Replace 'nom' with the actual property you want to use for the string representation
        return (string) $this->id_utilisateur;
    }







}