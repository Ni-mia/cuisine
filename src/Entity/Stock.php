<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stock.show','stock.list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('stock.restaurant','stock.create')]
    private ?Restaurant $idRestaurant = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('stock.ingredients','stock.create')]
    private ?Ingredients $idIngredient = null;

    #[ORM\Column]
    #[Groups(['stock.show', 'stock.create', 'stock.update','stock.list'])]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['stock.show', 'stock.create', 'stock.update','stock.list'])]
    private ?\DateTimeInterface $dt = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[Groups('stock.typemvt','stock.create')]
    private ?TypeMvt $idType = null;

    #[ORM\Column(nullable: true)]
    #[Groups('stock.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRestaurant(): ?Restaurant
    {
        return $this->idRestaurant;
    }

    public function setIdRestaurant(?Restaurant $idRestaurant): static
    {
        $this->idRestaurant = $idRestaurant;

        return $this;
    }

    public function getIdIngredient(): ?Ingredients
    {
        return $this->idIngredient;
    }

    public function setIdIngredient(?Ingredients $idIngredient): static
    {
        $this->idIngredient = $idIngredient;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getIdType(): ?typeMvt
    {
        return $this->idType;
    }

    public function setIdType(?typeMvt $idType): static
    {
        $this->idType = $idType;

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
