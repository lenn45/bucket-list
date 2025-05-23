<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/register', name: 'user_register')]
public function register(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $encoder
): Response {
    $user = new User();

    // Définir la date de création de l'utilisateur
    $user->setDateCreated(new \DateTime());

    // Création du formulaire d'inscription
    $registerForm = $this->createForm(RegisterType::class, $user);
    $registerForm->handleRequest($request);

    if ($registerForm->isSubmitted() && $registerForm->isValid()) {
        // Hachage du mot de passe
        $hashedPassword = $encoder->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        // Sauvegarde de l'utilisateur en base de données
        $em->persist($user);
        $em->flush();

        // Redirection ou message de succès
        return $this->redirectToRoute('login'); // À adapter selon votre route d'accueil
    }

    // Rendu de la vue du formulaire
    return $this->render('user/register.html.twig', [
        'registerForm' => $registerForm->createView(),
    ]);
}

#[Route('/login', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    $error = $authenticationUtils->getLastAuthenticationError();

    if ($error) {
        $this->addFlash('error', 'Invalid credentials. Please try again.');
    }

    return $this->render('user/login.html.twig');
}

#[Route('/logout', name:'logout')]
public function logout() {}

}
