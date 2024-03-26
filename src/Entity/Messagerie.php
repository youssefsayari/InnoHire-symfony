<?php

namespace App\Entity;

use App\Repository\MessagerieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagerieRepository::class)]
class Messagerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_message")]
    private ?int $id = null;

    #[ORM\Column(name: "date", type: "datetime")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $sender;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "reciver_id", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $reciver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getSender(): ?Utilisateur
    {
        return $this->sender;
    }

    public function setSender(?Utilisateur $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReciver(): ?Utilisateur
    {
        return $this->reciver;
    }

    public function setReciver(?Utilisateur $reciver): static
    {
        $this->reciver = $reciver;

        return $this;
    }
    
}
