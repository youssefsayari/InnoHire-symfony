<?php

namespace App\Entity;

use App\Repository\WalletQuizRepository;

use App\Repository\QuizRepository;

use Doctrine\ORM\Mapping as ORM;


  #[ORM\Entity(repositoryClass:WalletQuizRepository::class)]
 
class WalletQuiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_wallet_quiz;

    #[ORM\Column(name: 'id_quiz', type: 'integer')]
    private $id_quiz;

    #[ORM\Column(name: 'id_wallet', type: 'integer')]
    private $id_wallet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuiz(): ?int
    {
        return $this->id_quiz;
    }

    public function setIdQuiz(int $id_quiz): self
    {
        $this->id_quiz = $id_quiz;

        return $this;
    }

    public function getIdWallet(): ?int
    {
        return $this->id_wallet;
    }

    public function setIdWallet(int $id_wallet): self
    {
        $this->id_wallet = $id_wallet;

        return $this;
    }
    public function getQuiz(QuizRepository $quizRepository): ?Quiz
    {
        // Récupérer l'objet Quiz associé en utilisant l'ID du quiz dans cette entité WalletQuiz
        return $quizRepository->find($this->id_quiz);
    }
}
