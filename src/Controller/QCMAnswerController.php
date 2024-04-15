<?php
// QCMAnswerController.php

namespace App\Controller;

use App\Entity\QCM;
use App\Entity\StudentAnswer;
use App\Form\StudentAnswerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/qcm')]
class QCMAnswerController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route("/qcm/{id}/start", name:"app_qcm_start")]
    public function start(Request $request, Qcm $qcm): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $questions = $qcm->getQuestions();
        $totalQuestions = count($questions);
        $questionIndex = $request->query->getInt('question', 0);

        if ($questionIndex >= $totalQuestions) {
            return $this->redirectToRoute('app_qcm_results', ['id' => $qcm->getId()]);
        }

        $question = $questions[$questionIndex];
        $studentAnswer = new StudentAnswer();
        $studentAnswer->setUserId($this->getUser());
        $studentAnswer->setQcmId($qcm);
        $studentAnswer->setQuestionId($question);

        $form = $this->createForm(StudentAnswerType::class, $studentAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($studentAnswer);
            $entityManager->flush();

            if ($questionIndex < $totalQuestions - 1) {
                return $this->redirectToRoute('app_qcm_start', ['id' => $qcm->getId(), 'question' => $questionIndex + 1]);
            } else {
                return $this->redirectToRoute('app_qcm_results', ['id' => $qcm->getId()]);
            }
        }

        return $this->render('qcm_answer/question.html.twig', [
            'qcm' => $qcm,
            'question' => $question,
            'questions' => $questions,
            'form' => $form->createView(),
            'questionIndex' => $questionIndex + 1,
            'totalQuestions' => $totalQuestions,
        ]);
    }

    #[Route('/{id}/results', name: 'app_qcm_results', methods: ['GET'])]
    public function results(QCM $qcm): Response
    {
        $studentAnswers = $this->managerRegistry->getRepository(StudentAnswer::class)->findBy(['Qcm_id' => $qcm]);

        $score = 0;
        $totalQuestions = count($qcm->getQuestions());

        foreach ($studentAnswers as $studentAnswer) {
            if ($studentAnswer->getAnswersId()->isIsTrue()) {
                $score++;
            }
        }

        return $this->render('qcm_answer/results.html.twig', [
            'qcm' => $qcm,
            'score' => $score,
            'totalQuestions' => $totalQuestions,
            'studentAnswers' => $studentAnswers,
        ]);
    }

    #[Route("/qcm/{id}/answer", name: "app_qcm_answer", methods: ['POST'])]
    public function answer(Request $request, Qcm $qcm)
    {
        $em = $this->managerRegistry->getManager();
    
        $questions = $qcm->getQuestions();
    
        foreach ($questions as $question) {
            $form = $this->createFormBuilder()
                ->add('answer', ChoiceType::class, [
                    'choices' => $question->getAnswers(),
                    'expanded' => true,
                    'multiple' => false,
                    'choice_label' => 'label',
                ])
                ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $answer = $form->get('answer')->getData();
    
                $studentAnswer = new StudentAnswer();
                $studentAnswer->setQcmId($qcm);
                $studentAnswer->setQuestionid($question);
                $studentAnswer->setUserid($this->getUser());
    
                $em->persist($studentAnswer);
                $em->flush();
            }
        }
    
        return $this->redirectToRoute('app_qcm_answer_results', ['id' => $qcm->getId()]);
    }
}
