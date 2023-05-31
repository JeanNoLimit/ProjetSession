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
    #[Route('/formateur', name: 'liste_formateur', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        $formateurs=$em->getRepository(Formateur::class)->findby([],['nom'=>'ASC', 'prenom'=>'ASC']);

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs
        ]);
    }

    #[Route('/formateur/add', name: 'add_formateur', methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $formateur = new Formateur();
        $message='formateur ajouté';
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
            'formFormateur' => $formFormateur
        ]);
    }
}
