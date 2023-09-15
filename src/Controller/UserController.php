<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Repository\UserRepository;
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


    public function __construct(EntityManagerInterface $manager, UserRepository $user)
    {
        $this->manager = $manager;
        $this->user = $user;
    }


    //Création d'un utilisateur
    #[Route('/userCreate', name: 'user_create', methods: 'POST')]
    public function userCreate(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);


        $email = $data['email'];

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

            $user->setEmail($email)
                ->setPassword(sha1($password));

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

    #[Route('/userlogin', name: 'user_login', methods: 'POST')]
    public function userLogin(Request $request): Response
    {
        $user = $this->getUser();
        if ($user) {
            // L'utilisateur est authentifié
            return new JsonResponse(['status' => true, 'message' => 'Connecté avec succès'], Response::HTTP_OK);
        } else {
            // L'authentification a échoué
            return new JsonResponse(['status' => false, 'message' => 'Échec de l\'authentification'], Response::HTTP_UNAUTHORIZED);
        }
        return $this->render("user/index.html.twig");
    }

    #[Route('/login', name: 'login', methods: 'GET')]
    public function login(Request $request): Response
    {
        $form = $this->createForm(LoginFormType::class);

        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
