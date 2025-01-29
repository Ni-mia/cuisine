<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
class Ingredients extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['ingredients.show','ingredients.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ingredients.show', 'ingredients.create', 'ingredients.update','ingredients.list'])]
    private ?string $nom = null;

    /**
     * @var Collection<int, LiaisonPlatIngredients>
     */
    #[ORM\OneToMany(targetEntity: LiaisonPlatIngredients::class, mappedBy: 'idIngredient')]
    #[Groups('ingredients.liaisonPlatIngredients')]
    private Collection $liaisonPlatIngredients;

    #[ORM\Column(nullable: true)]
    #[Groups(['ingredients.list'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->liaisonPlatIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, LiaisonPlatIngredients>
     */
    public function getLiaisonPlatIngredients(): Collection
    {
        return $this->liaisonPlatIngredients;
    }

    public function addLiaisonPlatIngredient(LiaisonPlatIngredients $liaisonPlatIngredient): static
    {
        if (!$this->liaisonPlatIngredients->contains($liaisonPlatIngredient)) {
            $this->liaisonPlatIngredients->add($liaisonPlatIngredient);
            $liaisonPlatIngredient->setIdIngredients($this);
        }

        return $this;
    }

    public function removeLiaisonPlatIngredient(LiaisonPlatIngredients $liaisonPlatIngredient): static
    {
        if ($this->liaisonPlatIngredients->removeElement($liaisonPlatIngredient)) {
            // set the owning side to null (unless already changed)
            if ($liaisonPlatIngredient->getIdIngredients() === $this) {
                $liaisonPlatIngredient->setIdIngredients(null);
            }
        }

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
