<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @method User getUser()
 */
class UserController extends AbstractController
{
    #[Route('/users/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    #[Route('/users/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface$entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $params = $request->request->all();

        if ($request->isMethod(Request::METHOD_POST) && $this->isCsrfTokenValid('register', $params['token'])) {
            $user = new User();
            $user->setUsername($params['username']);
            $hashedPassword = $passwordHasher->hashPassword($user, $params['password']);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }


        return $this->render('user/register.html.twig');
    }

    #[Route('/users/logout', name: 'app_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): void
    {

    }
}
