<?php

// src/Controller/LearnerController.php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    
     #[Route("/users", name:"learner_list")]
    public function index(UserRepository $userRepository): Response
    {
        $learners = $userRepository;

        return $this->render('User/index.html.twig', [
            'learners' => $learners,
        ]);
    }
}
