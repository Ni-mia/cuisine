<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat extends AbstractDeletableEntity
{
    #[ORM\Id] 
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['plats.show','plats.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['plats.show', 'plats.create', 'plats.update','plats.list'])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(['plats.show', 'plats.create', 'plats.update','plats.list'])]
    private ?int $tempsDePreparation = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('plats.restaurant','plats.create')]
    private ?Restaurant $idRestaurant = null;

    /**
     * @var Collection<int, LiaisonPlatIngredients>
     */
    #[ORM\OneToMany(targetEntity: LiaisonPlatIngredients::class, mappedBy: 'idPlat')]
    #[Groups('plats.ingredient')]
    private Collection $liaisonPlatIngredients;

    #[ORM\Column(nullable: true)]
    #[Groups('plats.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, Prix>
     */
    #[ORM\OneToMany(targetEntity: Prix::class, mappedBy: 'idPlat')]
    private Collection $prixes;

    /**
     * @var Collection<int, DetailsCommande>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommande::class, mappedBy: 'idPlat')]
    private Collection $detailsCommandes;

    public function __construct()
    {
        $this->liaisonPlatIngredients = new ArrayCollection();
        $this->prixes = new ArrayCollection();
        $this->detailsCommandes = new ArrayCollection();
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

    public function getTempsDePreparation(): ?int
    {
        return $this->tempsDePreparation;
    }

    public function setTempsDePreparation(int $tempsDePreparation): static
    {
        $this->tempsDePreparation = $tempsDePreparation;

        return $this;
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
            $liaisonPlatIngredient->setIdPlat($this);
        }

        return $this;
    }

    public function removeLiaisonPlatIngredient(LiaisonPlatIngredients $liaisonPlatIngredient): static
    {
        if ($this->liaisonPlatIngredients->removeElement($liaisonPlatIngredient)) {
            // set the owning side to null (unless already changed)
            if ($liaisonPlatIngredient->getIdPlat() === $this) {
                $liaisonPlatIngredient->setIdPlat(null);
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

    /**
     * @return Collection<int, Prix>
     */
    public function getPrixes(): Collection
    {
        return $this->prixes;
    }

    public function addPrix(Prix $prix): static
    {
        if (!$this->prixes->contains($prix)) {
            $this->prixes->add($prix);
            $prix->setIdPlat($this);
        }

        return $this;
    }

    public function removePrix(Prix $prix): static
    {
        if ($this->prixes->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getIdPlat() === $this) {
                $prix->setIdPlat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes->add($detailsCommande);
            $detailsCommande->setIdPlat($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getIdPlat() === $this) {
                $detailsCommande->setIdPlat(null);
            }
        }

        return $this;
    }
}
