<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Recette::class)]
    private Collection $categorie_id;

    public function __construct()
    {
        $this->categorie_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getCategorieId(): Collection
    {
        return $this->categorie_id;
    }

    public function addCategorieId(Recette $categorieId): static
    {
        if (!$this->categorie_id->contains($categorieId)) {
            $this->categorie_id->add($categorieId);
            $categorieId->setCategorie($this);
        }

        return $this;
    }

    public function removeCategorieId(Recette $categorieId): static
    {
        if ($this->categorie_id->removeElement($categorieId)) {
            // set the owning side to null (unless already changed)
            if ($categorieId->getCategorie() === $this) {
                $categorieId->setCategorie(null);
            }
        }

        return $this;
    }
}
