<?php

namespace App\Service;

use App\Entity\Recette;
use App\Entity\User;
use App\Entity\FavoriteRecette;
use Doctrine\ORM\EntityManagerInterface;

class RecetteService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function getRecetteById($id)
    {
        return $this->entityManager->getRepository(Recette::class)->find($id);
    }
}
