<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_quiz", type: "integer")]

    private ?int $id = null;

    #[ORM\Column(name: "code_quiz", type: "integer")]
    #[Assert\NotBlank(message:"Le champ code_quiz ne peut pas être vide.")]

    private ?int $code_quiz = null;

    #[ORM\Column(name: "nom_quiz", type: "string", length: 255)]
    #[Assert\NotBlank(message:"Le champ nom_quiz ne peut pas être vide.")]

    private ?string $nom_quiz = null;

    #[ORM\Column(name: "description", type: "string", length: 255)]
    #[Assert\NotBlank(message:"Le champ niveau ne peut pas être vide.")]

    #[Assert\Choice(choices: ["Facile", "Moyen", "Difficile"], message: "Niveau should be 'Facile', 'Moyen', or 'Difficile'")]
    private ?string $description = null;

    #[ORM\Column(name: "prix_quiz", type: "integer")]
    #[Assert\NotBlank(message:"Le champ prix_quiz ne peut pas être vide.")]

   
    #[Assert\Range(min: 1, max: 50, minMessage: "Prix Quiz must be at least {{ 1 }}", maxMessage: "Prix Quiz cannot be greater than {{ 50 }}")]
    private ?int $prix_quiz = null;
    #[ORM\Column(name: "image_quiz", type: "string", length: 255)]
private ?string $image_quiz = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeQuiz(): ?int
    {
        return $this->code_quiz;
    }

    public function setCodeQuiz(int $code_quiz): self
    {
        $this->code_quiz = $code_quiz;
        return $this;
    }

    public function getNomQuiz(): ?string
    {
        return $this->nom_quiz;
    }

    public function setNomQuiz(string $nom_quiz): self
    {
        $this->nom_quiz = $nom_quiz;
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

    public function getPrixQuiz(): ?int
    {
        return $this->prix_quiz;
    }

    public function setPrixQuiz(int $prix_quiz): self
    {
        $this->prix_quiz = $prix_quiz;
        return $this;
    }

    public function getImageQuiz(): ?string
    {
        return $this->image_quiz;
    }

    public function setImageQuiz(string $image_quiz): self
    {
        $this->image_quiz = $image_quiz;
        return $this;
    }

    
}
