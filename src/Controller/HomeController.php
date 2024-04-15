<?php

// HomeController.php
namespace App\Controller;

use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CoursRepository $coursRepo): Response
    {
        $recentCourses = $coursRepo->findByRecentCourses(5);  // Exemple : 5 derniers cours
        $recommendedCourses = $coursRepo->findByRecommendedCourses(5);  // Exemple : 5 cours recommandés

        return $this->render('home/index.html.twig', [
            'recentCourses' => $recentCourses,
            'recommendedCourses' => $recommendedCourses,
        ]);
    }
}
