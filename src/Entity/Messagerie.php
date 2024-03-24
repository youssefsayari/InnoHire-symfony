<?php

namespace App\Entity;

use App\Repository\MessagerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagerieRepository::class)]
class Messagerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_message")]
    private ?int $id_message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE , name:"date")]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, referencedColumnName:"id_utilisateur")]
    private ?Utilisateur $sender;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:"reciver_id",nullable: false, referencedColumnName:"id_utilisateur")]
    private ?Utilisateur $receiver;

    public function getId(): ?int
    {
        return $this->id_message;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

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

    public function getSenderId(): ?Utilisateur
    {
        return $this->sender_id;
    }

    public function setSenderId(?Utilisateur $sender_id): static
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    public function getReciverId(): ?Utilisateur
    {
        return $this->reciver_id;
    }

    public function setReciverId(?Utilisateur $reciver_id): static
    {
        $this->reciver_id = $reciver_id;

        return $this;
    }
}
