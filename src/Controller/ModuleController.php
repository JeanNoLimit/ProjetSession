<?php

namespace App\Controller;

use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'liste_module')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $modules=$entityManager->getRepository(Module::class)->findAll();

        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }
}
