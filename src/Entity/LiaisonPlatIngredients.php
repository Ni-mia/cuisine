<?php

namespace App\Entity;

use App\Repository\LiaisonPlatIngredientsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: LiaisonPlatIngredientsRepository::class)]
class LiaisonPlatIngredients extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['liaisonPlatIngredients.show','liaisonPlatIngredients.list'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['liaisonPlatIngredients.show', 'liaisonPlatIngredients.create', 'liaisonPlatIngredients.update','liaisonPlatIngredients.list'])]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'liaisonPlatIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('liaisonPlatIngredients.plat', 'liaisonPlatIngredients.create')]
    private ?Plat $idPlat = null;

    #[ORM\ManyToOne(inversedBy: 'no')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('liaisonPlatIngredients.ingredients', 'liaisonPlatIngredients.create')]
    private ?Ingredients $idIngredients = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['liaisonPlatIngredients.list'])]
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdPlat(): ?Plat
    {
        return $this->idPlat;
    }

    public function setIdPlat(?Plat $idPlat): static
    {
        $this->idPlat = $idPlat;

        return $this;
    }

    public function getIdIngredients(): ?Ingredients
    {
        return $this->idIngredients;
    }

    public function setIdIngredients(?Ingredients $idIngredients): static
    {
        $this->idIngredients = $idIngredients;

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
