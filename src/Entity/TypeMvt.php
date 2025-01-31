<?php

namespace App\Entity;

use App\Repository\TypeMvtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeMvtRepository::class)]
class TypeMvt extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['typemvt.show','typemvt.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['typemvt.show', 'typemvt.create', 'typemvt.update','typemvt.list'])]
    private ?string $nom = null;

    
    #[ORM\Column]
    #[Groups(['typemvt.show', 'typemvt.create', 'typemvt.update','typemvt.list'])]
    private ?int $typeMvt = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'idType')]
    #[Groups('typemvt.typemvt')]
    private Collection $stocks;

    #[ORM\Column(nullable: true)]
    #[Groups('typemvt.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
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

    public function getTypeMvt(): ?int
    {
        return $this->typeMvt;
    }

    public function setTypeMvt(int $typeMvt): static
    {
        $this->typeMvt = $typeMvt;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setIdType($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getIdType() === $this) {
                $stock->setIdType(null);
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
