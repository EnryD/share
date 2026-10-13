<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\FichierType;
use App\Entity\Fichier;
use Doctrine\ORM\EntityManagerInterface;

final class FichierController extends AbstractController
{
    #[Route('/bibliotheque', name: 'app_bibliotheque')]
    public function index(): Response
    {
        return $this->render('fichier/index.html.twig', [
            'controller_name' => 'FichierController',
        ]);
    }

    #[Route('/bibliotheque/ajouter', name: 'app_fichier_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $em->persist($fichier);
                $em->flush();
                $this->addFlash('notice', 'Fichier ajouté à la bibliothèque');
                return $this->redirectToRoute('app_bibliotheque');
            }
        }
        return $this->render('fichier/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
