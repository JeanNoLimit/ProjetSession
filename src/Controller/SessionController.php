<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use Doctrine\ORM\EntityManager;
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
    #[Route('/session', name: 'liste_session', methods: ['GET'])]
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
    #[Route('/session/add', name: 'add_session', methods: ['GET','POST'])]
    #[Route('/session/edit/{id}', name:'edit_session', methods: ['GET','POST'])]
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
    #[Route('/session/delete/{id}', name : 'delete_session', methods :['GET'])]
    public function delete(EntityManagerInterface $entityManager, Session $session): Response {

        $entityManager->remove($session);
        $entityManager->flush();

        $this->addFlash('success', 'La session a bien été supprimée');

        return $this->redirectToRoute('liste_session');
    }

    
   
    /**
     * -Fonction affichage du détail d'une session
     * -Affichage de la liste des  modules programmées
     * -Affichage du formulaire pour programmer un nouveau module à la session
     * -Affichage de la liste des stagiaires inscrits
     * -Affichage du formulaire d'inscription des stagiaires à la session
     */
    #[Route('/session/show/{id}', name : 'show_session')]
    #[Route('/session/addProgram/{id}/{idModule}', name : 'add_program', methods : ['POST'])]
    #[Route('/session/add_student_to_session/{id}/{stagiaire}', name : 'add_student_to_session', methods : ['POST'])]
    public function show(EntityManagerInterface $entityManager,Module $module=null, Programme $programme=null,Stagiaire $stagiaire=null, int $id, int $idModule=null): Response
    { 
        
        $session=$entityManager->getRepository(Session::class)->find($id);
       
        
        //Ajout d'un module dans une session
        if(isset($_POST['submit'])){
           
            $nbJours=filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);
            
            if($nbJours){
                if($nbJours>0){
                    $module=$entityManager->getRepository(Module::class)->find($idModule);
                    $programme=new Programme();
                    $programme->setModule($module);
                    $programme->setSession($session);
                    $programme->setNbJours($nbJours);
                    $entityManager->persist($programme);
                    $entityManager->flush();
                    // var_dump($programme);die;
                    $this->addFlash('success', 'Le module a été ajouté à la session session');
                    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
                }else{
                    $this->addFlash('alert', 'Veuillez rentrer un nombre de jours positif et non nul');
                    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
                }
            } 
        }

        // Ajout d'un stagiaire dans une session
        if(isset($_POST['submitStudent'])){

            $nbPlaces=$session->getNbPlaces();
            $nbInscrit=count($session->getStagiaires());
            // var_dump($nbPlaces);var_dump($nbInscrit);die;
            if($nbInscrit<$nbPlaces){
                $session->addStagiaire($stagiaire);
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($session);
                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
                $this->addFlash('success', 'Le stagiaire a été ajouté à la session');
                return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
            }else{
                $this->addFlash('alert', 'Vous avez dépassé le nombre de place autorisé pour la session');
                return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
            }
            
           
        }

        
        $modules=$entityManager->getRepository(Session::class)->findUnprogrammedModules($id, $entityManager);
        $stagiaires=$entityManager->getRepository(Session::class)->findStudents($id, $entityManager);
        // var_dump($stagiaires);die;
        

        return $this->render('/session/show.html.twig', [
            'session' => $session,
            'modules' => $modules,
            'stagiaires' => $stagiaires

        ]);
    }


    /**
     * Fonction pour désinscrire un stagiaire à une session.
     */
    #[route('/session/deleteInscription/{session}/{stagiaire}', name:'delete_inscription', methods : ['GET'])]
    public function deleteInscription( Session $session, Stagiaire $stagiaire, EntityManagerInterface  $em): Response
    {
        
        $session->removeStagiaire($stagiaire);

         // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($session);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $this->addFlash('success', 'le stagiaire a été retiré de la session');

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);

    }

}
