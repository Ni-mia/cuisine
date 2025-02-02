<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['paiement.show','paiement.list'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups('paiement.commande','paiement.create','paiement.list')]
    private ?Commande $idCommande = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Groups(['paiement.show', 'paiement.update','paiement.list'])]
    private ?string $Total = null;

    #[ORM\Column]
    #[Groups(['paiement.show', 'paiement.create', 'paiement.update','paiement.list'])]
    private ?int $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['paiement.show', 'paiement.create', 'paiement.update','paiement.list'])]
    private ?\DateTimeInterface $dt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('paiement.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCommande(): ?commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?commande $idCommande): static
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->Total;
    }

    public function setTotal(string $Total): static
    {
        $this->Total = $Total;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDt(): ?\DateTimeInterface
    {
        return $this->dt;
    }

    public function setDt(\DateTimeInterface $dt): static
    {
        $this->dt = $dt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
