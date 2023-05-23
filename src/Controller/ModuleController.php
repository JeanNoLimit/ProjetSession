<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{

    /**
     * Fonction affichage de la liste des modules
     */
    #[Route('/module', name: 'liste_module')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $modules=$entityManager->getRepository(Module::class)->findAll();

        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }


    /**
     * fonction d'ajout et modification des modules
     */
    #[Route('/module/add', name: 'add_module')]
    #[Route('/module/edit/{id}', name: 'edit_module')]
    public function add(EntityManagerInterface $entityManager, Module $module= null,Request $request): Response
    {
        if(!$module) {
            $module= new Module();
        }

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $module = $form->getData();
            $entityManager->persist($module);

            $entityManager->flush();

            $this->addFlash('success', 'Le module a bien été ajoutée.');

            return $this->redirectToRoute('liste_module');
        }

        return $this->render('module/add.html.twig', [
            'form' => $form,
        ]);
    }

}
