<?php

namespace App\Entity;

use App\Repository\UtilisateurLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurLikeRepository::class)]



 
 
class UtilisateurLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_utilisateur_like", type: "integer")]
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column(type: "integer", nullable: true)]
    private $id_post;

    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column(type: "integer", nullable: true)]
    private $id_utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPost(): ?int
    {
        return $this->id_post;
    }

    public function setIdPost(int $id_post): self
    {
        $this->id_post = $id_post;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(int $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

}
