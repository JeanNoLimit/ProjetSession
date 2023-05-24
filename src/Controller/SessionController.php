<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    /**
     * Renvoie l'affichage de la liste des sessions 
     */
    #[Route('/session', name: 'liste_session')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sessions=$entityManager->getRepository(Session::class)->findBy([],['dateDebut'=>'ASC']);



        return $this->render('session/index.html.twig', [
            'sessions'=> $sessions
        ]);
    }


    /**
     * Renvoie l'affichage du formulaire + gestion du formulaire + modification du formulaire
     * 
     */
    #[Route('/session/add', name: 'add_session')]
    #[Route('/session/edit/{id}', name:'edit_session')]
    public function add(EntityManagerInterface $entityManager,Session $session = null, Request $request): Response
    {
        if(!$session){
             $session = new Session();
             $message="Session ajoutée";
        }
        else{$message="Session modifiée";}
       

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session=$form->getData();
            if($session->getDateDebut()<$session->getDateFin()){
                $entityManager->persist($session);
                $entityManager->flush();
    
                $this->addFlash('success', $message);
    
                return $this->redirectToRoute('liste_session');
            }else{
                $this->addFlash('alert', 'La date de début de formation doit être inférieur à la date de fin');
    
                return $this->redirectToRoute('add_session');
            }
        }

        return $this->render('session/add.html.twig', [
            'formSession' => $form,
        ]);
    }


    /**
     * Fonction de suppression d'une session
     */
    #[Route('/session/delete/{id}', name : 'delete_session')]
    public function delete(EntityManagerInterface $entityManager, Session $session):response {

        $entityManager->remove($session);
        $entityManager->flush();

        $this->addFlash('success', 'La session a bien été supprimée');

        return $this->redirectToRoute('liste_session');
    }
}
