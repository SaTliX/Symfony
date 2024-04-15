<?php

namespace App\Controller;

use App\Entity\StudentAnswer;
use App\Form\StudentAnswerType;
use App\Repository\StudentAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student/answer')]
class StudentAnswerController extends AbstractController
{
    #[Route('/', name: 'app_student_answer_index', methods: ['GET'])]
    public function index(StudentAnswerRepository $studentAnswerRepository): Response
    {
        return $this->render('student_answer/index.html.twig', [
            'student_answers' => $studentAnswerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_student_answer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $studentAnswer = new StudentAnswer();
        $form = $this->createForm(StudentAnswerType::class, $studentAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($studentAnswer);
            $entityManager->flush();

            return $this->redirectToRoute('app_student_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student_answer/new.html.twig', [
            'student_answer' => $studentAnswer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_answer_show', methods: ['GET'])]
    public function show(StudentAnswer $studentAnswer): Response
    {
        return $this->render('student_answer/show.html.twig', [
            'student_answer' => $studentAnswer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_answer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StudentAnswer $studentAnswer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudentAnswerType::class, $studentAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_student_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student_answer/edit.html.twig', [
            'student_answer' => $studentAnswer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_answer_delete', methods: ['POST'])]
    public function delete(Request $request, StudentAnswer $studentAnswer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studentAnswer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($studentAnswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_student_answer_index', [], Response::HTTP_SEE_OTHER);
    }
}
