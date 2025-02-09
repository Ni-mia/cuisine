<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['utilisateur.show','utilisateur.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['utilisateur.show', 'utilisateur.create', 'utilisateur.update','utilisateur.list'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['utilisateur.show', 'utilisateur.create', 'utilisateur.update','utilisateur.list'])]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    #[Groups(['utilisateur.show', 'utilisateur.create', 'utilisateur.update','utilisateur.list'])]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Groups(['utilisateur.show', 'utilisateur.create', 'utilisateur.update','utilisateur.list'])]
    private ?string $nomUtilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('utilisateur.role')]
    private ?Role $idRole = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'idUtilisateur')]
    #[Groups('utilisateur.commande')]
    private Collection $commandes;

    #[ORM\Column(nullable: true)]
    #[Groups('utilisateur.list')]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(length: 255,nullable: true)]
    #[Groups(['utilisateur.show', 'utilisateur.create', 'utilisateur.update','utilisateur.list'])]
    private ?string $FirebaseId = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
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

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): static
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    public function getIdRole(): ?role
    {
        return $this->idRole;
    }

    public function setIdRole(?role $idRole): static
    {
        $this->idRole = $idRole;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getIdUtilisateur() === $this) {
                $commande->setIdUtilisateur(null);
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

    public function getFirebaseId(): ?string
    {
        return $this->FirebaseId;
    }

    public function setFirebaseId(string $FirebaseId): static
    {
        $this->FirebaseId = $FirebaseId;

        return $this;
    }
}
