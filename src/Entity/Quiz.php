<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( name:"id_quiz",type:"integer")]
    private ?int $id_quiz = null;

    #[ORM\Column]
    private ?int $code_quiz = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_quiz = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix_quiz = null;

    #[ORM\Column(length: 255)]
    private ?string $image_quiz = null;

    public function getId(): ?int
    {
        return $this->id_quiz;
    }

    public function getCodeQuiz(): ?int
    {
        return $this->code_quiz;
    }

    public function setCodeQuiz(int $code_quiz): static
    {
        $this->code_quiz = $code_quiz;

        return $this;
    }

    public function getNomQuiz(): ?string
    {
        return $this->nom_quiz;
    }

    public function setNomQuiz(string $nom_quiz): static
    {
        $this->nom_quiz = $nom_quiz;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixQuiz(): ?int
    {
        return $this->prix_quiz;
    }

    public function setPrixQuiz(int $prix_quiz): static
    {
        $this->prix_quiz = $prix_quiz;

        return $this;
    }

    public function getImageQuiz(): ?string
    {
        return $this->image_quiz;
    }

    public function setImageQuiz(string $image_quiz): static
    {
        $this->image_quiz = $image_quiz;

        return $this;
    }
}
