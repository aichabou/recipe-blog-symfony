<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{

    private $manager;
    private $user;
    private $passwordHasher;


    public function __construct(EntityManagerInterface $manager, UserRepository $user,  UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->passwordHasher = $passwordHasher;
    }


    //Création d'un utilisateur
    #[Route('/userCreate', name: 'user_create', methods: 'POST')]
    public function userCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];

        //Vérifier si l'email existe déjà
        $email_exist = $this->user->findOneByEmail($email);

        if ($email_exist) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Cet email existe déjà, veuillez le changer'
                ],
                Response::HTTP_CONFLICT
            );
        } else {
            $user = new User();

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

            $user->setEmail($email)
                ->setUsername($username)
                ->setPassword($this->passwordHasher->hashPassword($user, $password));

            $this->manager->persist($user);
            $this->manager->flush();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'L\'utilisateur créé avec succès'
                ],
                Response::HTTP_CREATED
            );
        }
    }

    //Liste des utilisateurs
    #[Route('/getAllUsers', name: 'get_allusers', methods: 'GET')]
    public function getAllUsers(): Response
    {
        $users = $this->user->findAll();

        return $this->json($users, 200);
    }
    #[Route('/makeAdmin', name: 'make_admin', methods: 'PUT')]
    public function makeAdmin(): JsonResponse
    {
        $user = $this->user->findOneBy(['role' => 'user']);

        if (!$user) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Utilisateur non trouvé'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // Mettre à jour le rôle de l'utilisateur et le rendre administrateur
        $user->setAsAdmin();
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'L\'utilisateur a été promu administrateur avec succès'
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/userDelete', name: 'user_delete', methods: 'DELETE')]
    public function userDelete(): JsonResponse
    {
        $user = $this->user->findOneBy(['role' => 'user']);

        if (!$user) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Utilisateur non trouvé'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // Supprimer l'utilisateur de la base de données
        $this->manager->remove($user);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'L\'utilisateur a été supprimé avec succès'
            ],
            Response::HTTP_OK
        );
    }
}
