<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_question")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(name: 'id_quiz', referencedColumnName: 'id_quiz', nullable: false)]
    private ?Quiz $id_quiz = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ question ne peut pas être vide.")]

    private ?string $question = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ choix ne peut pas être vide.")]
    #[Assert\Regex(pattern:"/^\s*(\d\)\s*.*?){3}$/", message:"Le format du champ Choix doit etre 1)....2)...3).....")]

    private ?string $choix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le champ reponse_correcte ne peut pas être vide.")]
    #[Assert\Range(min:1, max:3, minMessage:"La réponse correcte doit être entre 1 et 3.", maxMessage:"La réponse correcte doit être entre 1 et 3.")]


    private ?int $reponse_correcte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuiz(): ?Quiz
    {
        return $this->id_quiz;
    }

    public function setIdQuiz(?Quiz $id_quiz): static
    {
        $this->id_quiz = $id_quiz;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getChoix(): ?string
    {
        return $this->choix;
    }

    public function setChoix(string $choix): static
    {
        $this->choix = $choix;

        return $this;
    }

    public function getReponseCorrecte(): ?int
    {
        return $this->reponse_correcte;
    }

    public function setReponseCorrecte(int $reponse_correcte): static
    {
        $this->reponse_correcte = $reponse_correcte;

        return $this;
    }
}
