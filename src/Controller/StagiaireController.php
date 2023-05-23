<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'liste_stagiaire')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $stagiaires=$entityManager->getRepository(Stagiaire::class)->findby([], ['nom' => 'ASC']);

        
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }
}