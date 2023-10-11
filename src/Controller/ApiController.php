<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\PasswordVerifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    private $entityManager;
    private $passwordVerifier;

    public function __construct(EntityManagerInterface $entityManager, PasswordVerifier $passwordVerifier)
    {
        $this->entityManager = $entityManager;
        $this->passwordVerifier = $passwordVerifier;
    }

    #[Route('/api/login', name: 'api_login', methods: 'POST')]
    public function apiLogin(Request $request, JWTTokenManagerInterface $jwtManager, KernelInterface $kernel): JsonResponse
    {
        // Récupérez les données d'authentification depuis la requête
        $requestData = json_decode($request->getContent(), true);
        $email = $requestData['email'];
        $password = $requestData['password'];

        // Effectuez la vérification des informations d'identification
        $user = $this->entityManager->getRepository(\App\Entity\User::class)->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordVerifier->verifyPassword($user, $password)) {
            return new JsonResponse(['message' => 'Authentification échouée'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        // Récupérer les rôles de l'utilisateur
        $roles = $user->getRoles();

        // Générez un token JWT pour l'utilisateur
        $token = $jwtManager->create($user);

        // Ajoutez un message de débogage pour vérifier si le token est généré
        $this->addFlash('jwt_token', $token);

        // Renvoyez le token en réponse JSON
        return new JsonResponse(['token' => $token, 'roles' => $roles]);
    }
}
