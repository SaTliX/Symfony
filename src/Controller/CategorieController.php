<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'categorie_show')]
    public function show(?Categorie $categorie): Response
    {
        if(!$categorie){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('categorie/show.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie,
    ]);
}
    
}
