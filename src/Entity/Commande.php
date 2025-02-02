<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande extends AbstractDeletableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['commande.show','commande.list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('commande.utilisateur','commande.create')]
    private ?Utilisateur $idUtilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('commande.restaurant','commande.create')]
    private ?restaurant $idRestaurant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['commande.show', 'commande.create', 'commande.update','commande.list'])]
    private ?\DateTimeInterface $dt = null;

    /**
     * @var Collection<int, DetailsCommande>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommande::class, mappedBy: 'idCommande')]
    #[Groups('commande.detailsCommande')]
    private Collection $detailsCommandes;

    #[ORM\Column(nullable: true)]
    #[Groups('commande.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
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

    public function getIdUtilisateur(): ?utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?utilisateur $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdRestaurant(): ?restaurant
    {
        return $this->idRestaurant;
    }

    public function setIdRestaurant(?restaurant $idRestaurant): static
    {
        $this->idRestaurant = $idRestaurant;

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
            $detailsCommande->setIdCommande($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getIdCommande() === $this) {
                $detailsCommande->setIdCommande(null);
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
