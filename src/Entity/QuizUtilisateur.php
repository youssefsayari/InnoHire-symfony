<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizUtilisateurRepository::class)]
class QuizUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_quiz_utilisateur_id = null;

    #[ORM\Column(type: "integer")]
    private ?int $utilisateurId = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_quiz = null;

    #[ORM\Column(type: "integer")]
    private ?int $score = null;

    public function getId(): ?int
    {
        return $this->id_quiz_utilisateur_id;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;
        return $this;
    }

    public function getId_quiz(): ?int
    {
        return $this->id_quiz;
    }

    public function setId_quiz(int $id_quiz): self
    {
        $this->id_quiz= $id_quiz;
        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }
}
