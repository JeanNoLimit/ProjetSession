<?php

namespace App\Controller;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $sessions=$em->getRepository(Session::class)->findBy([],['dateDebut'=>'ASC']);

        return $this->render('home/index.html.twig', [
            'sessions' => $sessions
        ]);
    }
}
