<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $chemin_fichier = null;

    #[ORM\OneToMany(mappedBy: 'no', targetEntity: Recette::class)]
    private Collection $recette_id;

    public function __construct()
    {
        $this->recette_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheminFichier(): ?string
    {
        return $this->chemin_fichier;
    }

    public function setCheminFichier(string $chemin_fichier): static
    {
        $this->chemin_fichier = $chemin_fichier;

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
        }

        return $this;
    }
}
