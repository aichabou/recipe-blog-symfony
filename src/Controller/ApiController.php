<?php

// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PasswordVerifier;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

/**
 * @Route("/api")
 * @CrossOrigin(origins={"http://localhost:8080"}, methods={"GET","POST","PUT","DELETE"}, headers={"Content-Type", "Authorization"})
 */
class ApiController extends AbstractController
{
    private $entityManager;
    private $passwordVerifier;

    public function __construct(EntityManagerInterface $entityManager, PasswordVerifier $passwordVerifier)
    {
        $this->entityManager = $entityManager;
        $this->passwordVerifier = $passwordVerifier;
    }

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     * 
     */
    #[Route('/api/login', name: 'api_login', methods: 'POST')]
    public function apiLogin(Request $request, JWTTokenManagerInterface $jwtManager, KernelInterface $kernel): JsonResponse
    {
        // Récupérez les données d'authentification depuis la requête (par exemple, e-mail et mot de passe)

        $requestData = json_decode($request->getContent(), true);

        $email = $requestData['email'];
        $password = $requestData['password'];


        // Effectuez la vérification des informations d'identification ici (par exemple, vérification en base de données)

        // Si les informations d'identification sont valides, récupérez l'utilisateur
        $user = $this->entityManager->getRepository(\App\Entity\User::class)->findOneBy(['email' => $email]);


        if (!$user || !$this->passwordVerifier->verifyPassword($user, $password)) {
            return new JsonResponse(['message' => 'Authentification échouée'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Générez un token JWT pour l'utilisateur
        $token = $jwtManager->create($user);

        // Ajoutez un message de débogage pour vérifier si le token est généré
        $this->addFlash('jwt_token', $token);

        // Renvoyez le token en réponse JSON
        return new JsonResponse(['token' => $token]);
    }
}
