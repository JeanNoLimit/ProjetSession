<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * Affichage de la vue Profil utilisateur
     *
     * @return Response
     */
    #[Route(path: '/security/viewProfile', name: 'view_profile')]
    public function viewProfile(): Response
    {
        return $this->render('security/viewProfile.html.twig');
    }


    /**
     * Affichage de la page administration. Permet de gérer les comptes utilisateurs, modifier leurs rôles, supprimer un compte.
     *
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route(path: 'security/admin', name: 'administration')]
    public function administration(EntityManagerInterface $em):Response
    {

        $users= $em->getRepository(User::class)->findBy([],['nom'=>'ASC', 'prenom'=>'ASC']);

        return $this->render('security/admin.html.twig',[
            'users' => $users
        ]);
    }

}
