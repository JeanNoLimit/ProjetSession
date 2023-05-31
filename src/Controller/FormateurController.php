<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{

    /**
     * Affichage de la liste des formateurs
     *
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/formateur', name: 'liste_formateur', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        $formateurs=$em->getRepository(Formateur::class)->findby([],['nom'=>'ASC', 'prenom'=>'ASC']);

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs
        ]);
    }

    /**
     * Affichage du formulaire + edition formateur
     */
    #[Route('/formateur/add', name: 'add_formateur', methods: ['GET', 'POST'])]
    #[Route('/formateur/edit/{id}', name: 'edit_formateur', methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $em, Formateur $formateur=null, Request $request): Response
    {
        if(!$formateur){
            $formateur = new Formateur();
            $message='formateur ajouté';
        }
        else{
            $message='formateur modifié';
        }
       
        $formFormateur= $this->createForm(FormateurType::class, $formateur);

        $formFormateur->handleRequest($request);

        if ($formFormateur->isSubmitted() && $formFormateur->isValid()) {

            $formateur= $formFormateur->getData();
            $em->persist($formateur);

            $em->flush();

            $this->addFlash('success', $message);

            return $this->redirectToRoute('liste_formateur');
        }

        return $this->render('formateur/add.html.twig', [
            'formFormateur' => $formFormateur,
            'edit'=>$formateur->getId(),
            'formateur' => $formateur
        ]);
    }

    /**
     * Fonction suppression d'un formateur
     */
    #[Route('/formateur/{id}/delete', name : 'delete_formateur')]
    public function delete_formateur(EntityManagerInterface $em, Formateur $formateur){
       
        $em->remove($formateur);
        $em->flush();

        $this->addFlash('success', 'Le formateur a bien été supprimé.');

        return $this->redirectToRoute('liste_formateur');
    }
}
