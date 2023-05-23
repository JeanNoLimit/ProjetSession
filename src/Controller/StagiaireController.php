<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/stagiaire/{id}/show', name: 'show_stagiaire')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $stagiaire= $entityManager->getRepository(Stagiaire::class)->find($id);

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' =>$stagiaire
        ]);
    }

    /**
     * fonction affichage formulaire nouveau stagiaire et modification
     */
    #[route('/stagiaire/add', name:'add_stagiaire')]
    public function add(EntityManagerInterface $entityManager, Stagiaire $stagiaire = null, Request $request):Response
    {
        $stagiaire=new Stagiaire();

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire= $form->getData();
            $entityManager->persist($stagiaire);

            $entityManager->flush();

            $this->addFlash('success', 'Le stagiaire a bien été ajoutée.');

            return $this->redirectToRoute('liste_stagiaire');
        }
        return $this->render('stagiaire/add.html.twig',[
            'formStagiaire' => $form
        ]);
    }
}
