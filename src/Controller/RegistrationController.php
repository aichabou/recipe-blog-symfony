<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    private $manager;
    private $user;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $manager, UserRepository $user, UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'register', methods: 'POST')]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];

        $existingUser = $this->user->findOneByEmail($email);

        if ($existingUser) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Cet email existe déjà, veuillez le changer'
            ], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setEmail($email)
            ->setUsername($username)
            ->setPassword($hashedPassword);

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse([
            'status' => true,
            'message' => 'L\'utilisateur créé avec succès'
        ], Response::HTTP_CREATED);
    }
}
