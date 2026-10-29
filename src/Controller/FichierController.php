<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\FichierType;
use App\Entity\Fichier;
use App\Entity\Categorie;
use App\Repository\FichierRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

final class FichierController extends AbstractController
{
    #[Route('/bibliotheque', name: 'app_bibliotheque')]
    public function index(FichierRepository $fichierRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('fichier/index.html.twig', [
            'fichiers' => $fichierRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'categorieActive' => null,
        ]);
    }

    #[Route('/bibliotheque/categorie/{id}', name: 'app_bibliotheque_categorie')]
    public function parCategorie(Categorie $categorie, FichierRepository $fichierRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('fichier/index.html.twig', [
            'fichiers' => $fichierRepository->findBy(['categorie' => $categorie]),
            'categories' => $categorieRepository->findAll(),
            'categorieActive' => $categorie,
        ]);
    }

    #[Route('/private/bibliotheque/ajouter', name: 'app_fichier_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $fichierEnvoye = $form->get('fichier')->getData();
                $extension = strtolower($fichierEnvoye->getClientOriginalExtension());
                if($fichierEnvoye->isValid() && ($extension == 'pdf' || $extension == 'txt' || $extension == 'doc' || $extension == 'docx' || $extension == 'odt' || $extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'))
                {
                    $nomFichier = $fichierEnvoye->getClientOriginalName();
                    $fichierEnvoye->move($this->getParameter('kernel.project_dir').'/public/fichiers', $nomFichier);
                    $fichier->setNomFichier($nomFichier);
                    $em->persist($fichier);
                    $em->flush();
                    $this->addFlash('notice', 'Fichier ajouté à la bibliothèque');
                    return $this->redirectToRoute('app_bibliotheque');
                }
                $this->addFlash('notice', 'Fichier refusé : type non autorisé ou trop gros');
            }
        }
        return $this->render('fichier/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
