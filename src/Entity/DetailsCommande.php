<?php

namespace App\Entity;

use App\Repository\DetailsCommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DetailsCommandeRepository::class)]
class DetailsCommande extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['detailsCommande.show','detailsCommande.list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'detailsCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('detailsCommande.restaurant','detailsCommande.show','detailsCommande.create','detailsCommande.list')]
    private ?Commande $idCommande = null;

    #[ORM\ManyToOne(inversedBy: 'detailsCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('detailsCommande.restaurant','detailsCommande.show','detailsCommande.create','detailsCommande.list')]
    private ?Plat $idPlat = null;

    #[ORM\Column]
    #[Groups(['detailsCommande.show', 'detailsCommande.create', 'detailsCommande.update','detailsCommande.list'])]
    private ?int $statut = null;

    #[ORM\Column(nullable: true)]
    #[Groups('detailsCommande.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getIdPlat(): ?plat
    {
        return $this->idPlat;
    }

    public function setIdPlat(?plat $idPlat): static
    {
        $this->idPlat = $idPlat;

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
