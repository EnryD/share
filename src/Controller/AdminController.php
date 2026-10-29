<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class AdminController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'app_admin_utilisateurs')]
    public function listeUtilisateurs(UserRepository $userRepository): Response
    {
        $utilisateurs = $userRepository->findBy([], ['dateInscription' => 'DESC']);
        return $this->render('admin/liste-utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
