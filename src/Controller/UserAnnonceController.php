<?php

namespace App\Controller;

use App\Entity\Annonce;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAnnonceController extends AbstractController
{
    #[Route('/user/annonce', name: 'app_user_annonce')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $annonces = $doctrine->getRepository(Annonce::class)->findAll();
        return $this->render('user_annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }
}
