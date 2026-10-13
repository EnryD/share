<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CategorieType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CategorieController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice', 'Catégorie ajoutée');
                return $this->redirectToRoute('app_categories');
            }
        }
        return $this->render('categorie/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}
