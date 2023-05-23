<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{

    /**
     * Fonction renvoie vers l'affichage de la liste des catégories.
     * @var EntityManagerInterface
     */
    #[Route('/categorie', name: 'liste_categorie')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $categories=$entityManager->getRepository(Categorie::class)->findby([], ['nom' => 'ASC']);

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Formulaire d'ajout de catégorie et modification.
     * @param CategorieType $categorie
     * @return Response
     */

    #[Route('/categorie/add', name: 'add_categorie')]
    #[Route('/categorie/{id}/edit', name: "edit_categorie")]
    public function add(EntityManagerInterface $entityManager,Categorie $categorie = null, Request $request): Response
    {
        if(!$categorie) {
             $categorie=new Categorie();
        }
       
        //Création du formulaire
        $form =$this->createForm(CategorieType::class, $categorie);

        //Analyse de la requête du formulaire 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //getData récupère les données de l'entreprise si elle existe. (vue edit)
            $categorie= $form->getData();
            $entityManager->persist($categorie);

            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été ajoutée.');

            return $this->redirectToRoute('liste_categorie');

        }

        return $this->render('categorie/add.html.twig',[
            'form' => $form->createView(),
            'edit' => $categorie->getId(),
            'categorie' => $categorie
        ]);
    }
    /**
     * Fonction suppression d'une catégorie
     */

    #[Route('/categorie/delete/{id}', name: "delete_categorie")]
    public function delete(EntityManagerInterface $entityManager, Categorie $categorie): Response
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée.');

        return $this->redirectToRoute('liste_categorie');
    }


}
