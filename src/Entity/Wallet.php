<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_wallet")]
    private ?int $id_wallet = null;

    #[ORM\Column(nullable: true)]
    private ?int $balance = null;



    #[ORM\OneToOne(targetEntity: Etablissement::class)]
    #[ORM\JoinColumn(name: "id_etablissement", referencedColumnName: "id_etablissement",nullable: false)]
    private ?Etablissement $etablissement = null; 

   
    

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_c = null;

    #[ORM\Column]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id_wallet;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(?int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

   

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->date_c;
    }

    public function setDateC(\DateTimeInterface $date_c): static
    {
        $this->date_c = $date_c;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}