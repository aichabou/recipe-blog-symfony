<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageController extends AbstractController
{
    /**
     * @Route("/upload-image", name="upload_image")
     */
    public function uploadImage(Request $request): Response
    {
        // Récupérer le fichier image téléchargé
        $imageFile = $request->files->get('image');

        // Vérifier si un fichier a été téléchargé
        if ($imageFile) {
            // Déplacer le fichier téléchargé vers un répertoire de destination
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return new Response("Erreur lors du téléchargement de l'image.", Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new Response('Image téléchargée avec succès.', Response::HTTP_OK);
        }

        // aucun fichier n'a été téléchargé
        return new Response('Aucune image n\'a été téléchargée.', Response::HTTP_BAD_REQUEST);
    }
}
