<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Service\RecetteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    private $recetteService;
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/recette/add', name: 'app_recette')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new recette();

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', ['id' => $recette->getId()]);
        }

        return $this->render('recipe/recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param recette $recette
     * @return Response
     */
    #[Route('/recette/find/{titre}', name: 'app_recette_id')]
    public function show(EntityManagerInterface $entityManager, string $titre): Response
    {
        $query = $entityManager->createQuery(
            'SELECT r
            FROM App\Entity\recette r
            WHERE r.titre = :titre'
        )->setParameter('titre', $titre);

        $recette = $query->getOneOrNullResult();

        return $this->render('recipe/recipeId.html.twig', [
            'recette' => $recette
        ]);
    }

    /**
     * @param recette $recette
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/update/{recette}', name: 'app_recette_update')]
    public function update(recette $recette, Request $request): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($recette);
            $this->em->flush();
            return $this->redirectToRoute('app_recette');
        }
        return $this->render('recipe/recipe.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param recette $recette
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/delete/{recette}', name: 'app_recette_delete')]
    public function delete(recette $recette, Request $request): Response
    {
        $this->em->remove($recette);
        $this->em->flush();

        return $this->redirectToRoute("app_recette");
    }
}
