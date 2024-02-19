<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide.')]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le description ne peut pas être vide.')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le ingredients ne peut pas être vide.')]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le instructions ne peut pas être vide.')]
    private ?string $instructions = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le image ne peut pas être vide.')]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le video ne peut pas être vide.')]
    private ?string $video = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\OneToMany(mappedBy: 'Recette', targetEntity: User::class)]
    private Collection $user_id;

    #[ORM\ManyToOne(inversedBy: 'categorie_id')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'recette_id')]
    private ?Notation $notation = null;

    #[ORM\ManyToOne(inversedBy: 'recette_id')]
    private ?Commentaire $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'recette_id')]
    private ?Like $likes = null;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNotation(): ?Notation
    {
        return $this->notation;
    }

    public function setNotation(?Notation $notation): static
    {
        $this->notation = $notation;

        return $this;
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getLikes(): ?Like
    {
        return $this->likes;
    }

    public function setLikes(?Like $likes): static
    {
        $this->likes = $likes;

        return $this;
    }
}
