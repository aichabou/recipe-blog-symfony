<?php

namespace App\Entity;

use App\Repository\NotationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotationRepository::class)]
class Notation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ajout = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\OneToMany(mappedBy: 'notation', targetEntity: Recette::class)]
    private Collection $recette_id;

    public function __construct()
    {
        $this->recette_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): static
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

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
            $recetteId->setNotation($this);
        }

        return $this;
    }

    public function removeRecetteId(Recette $recetteId): static
    {
        if ($this->recette_id->removeElement($recetteId)) {
            // set the owning side to null (unless already changed)
            if ($recetteId->getNotation() === $this) {
                $recetteId->setNotation(null);
            }
        }

        return $this;
    }
}
