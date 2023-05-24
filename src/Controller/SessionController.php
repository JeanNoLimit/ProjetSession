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
    public function add(EntityManagerInterface $entityManager,Session $session = null, Request $request): Response
    {

        $session = new Session();

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session=$form->getData();
            if($session->getDateDebut()<$session->getDateFin()){
                $entityManager->persist($session);
                $entityManager->flush();
    
                $this->addFlash('success', 'formulaire enregistré');
    
                return $this->redirectToRoute('liste_session');
            }else{
                $this->addFlash('alert', 'La date de début de formation doit être inférieur à la date de fin');
    
                return $this->redirectToRoute('add_session');
            }
            
           
        }

        return $this->render('session/add.html.twig', [
            'formSession' => $form
        ]);
    }
}
