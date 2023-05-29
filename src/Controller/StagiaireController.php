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
    #[Route('/stagiaire', name: 'liste_stagiaire', methods : ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $stagiaires=$entityManager->getRepository(Stagiaire::class)->findby([], ['nom' => 'ASC', 'prenom' => 'ASC']);

        
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    /**
     * Fonction affichage détail d'un stagiaire
     */
    #[Route('/stagiaire/{id}/show', name: 'show_stagiaire', methods : ['GET'])]
    public function show( Stagiaire $stagiaire): Response
    {
      

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' =>$stagiaire
        ]);
    }

    
    /**
     * fonction affichage formulaire nouveau stagiaire et modification
     */
    #[Route('/stagiaire/add', name:'add_stagiaire', methods : ['GET','POST'])]
    #[Route('stagiaire/{id}/edit', name:'edit_stagiaire', methods : ['GET','POST'])]
    public function add(EntityManagerInterface $entityManager, Stagiaire $stagiaire = null, Request $request):Response
    {
        if(!$stagiaire){
            $message = "Stagiaire ajouté";
            $stagiaire=new Stagiaire();
        }
        else {$message = "Stagiaire modifié";}

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stagiaire= $form->getData();
            $entityManager->persist($stagiaire);

            $entityManager->flush();

            $this->addFlash('success', $message);

            return $this->redirectToRoute('liste_stagiaire');
        }
        return $this->render('stagiaire/add.html.twig',[
            'formStagiaire' => $form,
            'edit'=> $stagiaire->getId(),
            'stagiaire' => $stagiaire
        ]);
    }

    /**
     * Fonction de suppression d'un stagiaire
     */
    #[Route('/stagiaire/delete/{id}', name:'delete_stagiaire', methods : ['GET'])]
    public function delete(EntityManagerInterface $entityManager, Stagiaire $stagiaire): Response
    {
        
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        $this->addFlash('success', 'Le stagiaire a bien été supprimé.');

        return $this->redirectToRoute('liste_stagiaire');
    }

    



}
