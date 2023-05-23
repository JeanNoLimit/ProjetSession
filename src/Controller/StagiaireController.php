<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{

    /**
     * Fonction affichage lsite des stagiaires
     */
    #[Route('/stagiaire', name: 'liste_stagiaire')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $stagiaires=$entityManager->getRepository(Stagiaire::class)->findby([], ['nom' => 'ASC']);

        
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    /**
     * Fonction affichage détail d'un stagiaire
     */
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $stagiaire= $entityManager->getRepository(Stagiaire::class)->find($id);

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' =>$stagiaire
        ]);
    }
}
