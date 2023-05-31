<?php

namespace App\Controller;

use App\Entity\Formateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    #[Route('/formateur', name: 'liste_formateur')]
    public function index(EntityManagerInterface $em): Response
    {

        $formateurs=$em->getRepository(Formateur::class)->findby([],['nom'=>'ASC', 'prenom'=>'ASC']);

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs
        ]);
    }
}
