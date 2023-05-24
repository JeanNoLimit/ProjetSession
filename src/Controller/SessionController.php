<?php

namespace App\Controller;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'liste_session')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sessions=$entityManager->getRepository(Session::class)->findBy([],['dateDebut'=>'ASC']);



        return $this->render('session/index.html.twig', [
            'sessions'=> $sessions
        ]);
    }
}
