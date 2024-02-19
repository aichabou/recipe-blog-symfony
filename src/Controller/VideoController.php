<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/upload-video", name="upload_video", methods={"POST"})
     */
    public function uploadVideo(Request $request): Response
    {
        // Récupérer le fichier vidéo téléchargé
        $videoFile = $request->files->get('video');

        // Vérifier si un fichier a été téléchargé
        if ($videoFile) {
            // Déplacer le fichier téléchargé vers un répertoire de destination
            $newFilename = uniqid().'.'.$videoFile->guessExtension();

            try {
                $videoFile->move(
                    $this->getParameter('videos_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return new Response('Erreur lors du téléchargement de la vidéo.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new Response('Vidéo téléchargée avec succès.', Response::HTTP_OK);
        }

        // aucun fichier n'a été téléchargé
        return new Response('Aucune vidéo n\'a été téléchargée.', Response::HTTP_BAD_REQUEST);
    }
}
