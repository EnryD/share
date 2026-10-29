<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CategorieType;
use App\Form\SupprimerCategoriesType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CategorieController extends AbstractController
{
    #[Route('/private/categories', name: 'app_categories')]
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

    #[Route('/private/liste-categories', name: 'app_liste_categories')]
    public function listeCategories(Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SupprimerCategoriesType::class);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $categoriesCochees = $form->get('categories')->getData();
                foreach($categoriesCochees as $categorie){
                    $em->remove($categorie);
                }
                $em->flush();
                $this->addFlash('notice', 'Catégories supprimées');
                return $this->redirectToRoute('app_liste_categories');
            }
        }
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/liste-categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/private/categories/modifier/{id}', name: 'app_categorie_modifier')]
    public function modifier(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice', 'Catégorie modifiée');
                return $this->redirectToRoute('app_liste_categories');
            }
        }
        return $this->render('categorie/modifier.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/private/categories/supprimer/{id}', name: 'app_categorie_supprimer')]
    public function supprimer(Categorie $categorie, EntityManagerInterface $em): Response
    {
        if($categorie != null)
        $em->remove($categorie);
        $em->flush();
        $this->addFlash('notice', 'Catégorie supprimée');
        return $this->redirectToRoute('app_liste_categories');
    }
}
