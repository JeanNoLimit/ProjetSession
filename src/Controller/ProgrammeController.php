<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme/delete/{id}', name: 'delete_programme')]
    public function delete(EntityManagerInterface $entityManager, Programme $programme, int $idSession= null, int $id): Response
    {
        $idSession=$programme->getSession()->getId();
        $entityManager->remove($programme);
        $entityManager->flush();

        $this->addFlash('success', 'Le module à été retiré');

        return $this->redirectToRoute('show_session', ['id' => $idSession]);

    }
}
