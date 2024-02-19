<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[Route('/userCreate', name: 'user_create', methods: ['POST'])]
    public function userCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];

        // Validate and handle request data as needed

        $existingUser = $this->userRepo->findOneByEmail($email);

        if ($existingUser) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Cet email existe déjà, veuillez le changer'
                ],
                Response::HTTP_CONFLICT
            );
        }

        $userData = [
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ];

        $createdUser = $this->userRepo->createUser($userData);

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'L\'utilisateur créé avec succès',
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/userUpdate/{id}', name: 'user_update', methods: ['PUT'])]
    public function userUpdate(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepo->find($id);

        if (!$user) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Utilisateur non trouvé'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        // Validate and handle request data as needed

        $updatedUser = $this->userRepo->updateUser($user, $data);

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'L\'utilisateur a été mis à jour avec succès',
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/userDelete/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function userDelete(int $id): JsonResponse
    {
        $user = $this->userRepo->find($id);

        if (!$user) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Utilisateur non trouvé'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->userRepo->deleteUser($user);

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'L\'utilisateur a été supprimé avec succès'
            ],
            Response::HTTP_OK
        );
    }
}
