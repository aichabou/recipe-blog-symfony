<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: user::class)]
    private Collection $user_id;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: Recette::class)]
    private Collection $recette_id;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->recette_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(user $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
            $userId->setCommentaire($this);
        }

        return $this;
    }

    public function removeUserId(user $userId): static
    {
        if ($this->user_id->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getCommentaire() === $this) {
                $userId->setCommentaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecetteId(): Collection
    {
        return $this->recette_id;
    }

    public function addRecetteId(Recette $recetteId): static
    {
        if (!$this->recette_id->contains($recetteId)) {
            $this->recette_id->add($recetteId);
            $recetteId->setCommentaire($this);
        }

        return $this;
    }

    public function removeRecetteId(Recette $recetteId): static
    {
        if ($this->recette_id->removeElement($recetteId)) {
            // set the owning side to null (unless already changed)
            if ($recetteId->getCommentaire() === $this) {
                $recetteId->setCommentaire(null);
            }
        }

        return $this;
    }
}
