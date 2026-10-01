<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $nom = null;

        // Quand on clique sur "Envoyer", le formulaire arrive en POST
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
        }

        return $this->render('contact/index.html.twig', [
            'nom' => $nom,
        ]);
    }
}
