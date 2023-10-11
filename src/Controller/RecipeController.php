<?php

namespace App\Controller;

use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/recipe', name: 'recette_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Récupérer les données de la requête JSON

        $requestData = json_decode($request->getContent(), true);

        // Vérifiez si les clés existent dans le tableau $data
        if (!isset($requestData['titre'], $requestData['description'], $requestData['ingredients'], $requestData['instructions'], $requestData['image'], $requestData['video'])) {
            // Ajoutez un log pour afficher les données reçues
            error_log('Data received: ' . print_r($requestData, true));

            return $this->json(['error' => 'Données invalides'], 400); // Répondez avec un code d'erreur 400 (Bad Request)
        }

        // Créer une nouvelle recette
        $recette = new Recette();
        $recette->setTitre($requestData['titre']);
        $recette->setDescription($requestData['description']);
        $recette->setIngredients($requestData['ingredients']);
        $recette->setInstructions($requestData['instructions']);
        $recette->setImage($requestData['image']);
        $recette->setVideo($requestData['video']);

        // Enregistrer la recette dans la base de données
        $this->entityManager->persist($recette);
        $this->entityManager->flush();

        // Répondre avec la recette créée
        return $this->json(['message' => 'Recette créée avec succès', 'id' => $recette->getId()]);
    }

    #[Route('/recipe/{id}', name: 'recette_update', methods: ['PUT'])]
    public function update(Request $request, Recette $recette): JsonResponse
    {
        try {
            // Récupérer et valider les données de la requête JSON
            $data = json_decode($request->getContent(), true);
            // ... Effectuer la validation des données ici ...

            $query = $this->entityManager->createQuery('
                UPDATE App\Entity\Recette r
                SET r.titre = :titre, r.description = :description, r.ingredients = :ingredients, r.instructions = :instructions, r.image = :image, r.video = :video
                WHERE r.id = :id
            ')
                ->setParameter('titre', $data['titre'])
                ->setParameter('description', $data['description'])
                ->setParameter('ingredients', $data['ingredients'])
                ->setParameter('instructions', $data['instructions'])
                ->setParameter('image', $data['image'])
                ->setParameter('video', $data['video'])
                ->setParameter('id', $recette->getId());

            $query->execute();

            // Répondre avec un message de succès
            return $this->json(['message' => 'Recette mise à jour avec succès']);
        } catch (\Exception $e) {
            // Gérer les exceptions ici
            return $this->json(['error' => 'Erreur lors de la mise à jour de la recette'], 500);
        }
    }


    #[Route('/recipe/{id}', name: 'recette_delete', methods: ['DELETE'])]
    public function delete(Recette $recette): JsonResponse
    {
        // Supprimer la recette de la base de données
        $this->entityManager->remove($recette);
        $this->entityManager->flush();

        // Répondre avec un message de succès
        return $this->json(['message' => 'Recette supprimée avec succès']);
    }
}
