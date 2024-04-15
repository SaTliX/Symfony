<?php

namespace App\Controller;

use App\Entity\QCM;
use App\Entity\StudentAnswer;
use App\Form\QCMType;
use App\Form\StudentAnswerType;
use App\Repository\QCMRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Answers;
use App\Repository\QuestionRepository;
use App\Form\AnswersType; // Importing AnswerType
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


#[Route('/qcm')]
class QCMController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_q_c_m_index', methods: ['GET'])]
    public function index(QCMRepository $qCMRepository): Response
    {
        return $this->render('qcm/index.html.twig', [
            'q_c_ms' => $qCMRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_q_c_m_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QCMRepository $qCMRepository): Response
    {
        $qCM = new QCM();
        $form = $this->createForm(QCMType::class, $qCM);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qCMRepository->add($qCM, true);

            return $this->redirectToRoute('app_q_c_m_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qcm/new.html.twig', [
            'q_c_m' => $qCM,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_q_c_m_show', methods: ['GET'])]
    public function show(QCM $qCM): Response
    {
        return $this->render('qcm/show.html.twig', [
            'q_c_m' => $qCM,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_q_c_m_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QCM $qCM, QCMRepository $qCMRepository): Response
    {
        $form = $this->createForm(QCMType::class, $qCM);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qCMRepository->add($qCM, true);

            return $this->redirectToRoute('app_q_c_m_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qcm/edit.html.twig', [
            'q_c_m' => $qCM,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_q_c_m_delete', methods: ['POST'])]
    public function delete(Request $request, QCM $qCM, QCMRepository $qCMRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$qCM->getId(), $request->request->get('_token'))) {
            $qCMRepository->remove($qCM, true);
        }

        return $this->redirectToRoute('app_q_c_m_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route("/qcm/{id}/answer", name: "app_qcm_answer", methods: ['POST'])]
    public function answer(Request $request, Qcm $qcm)
    {
        $em = $this->entityManager;
    
        // Récupérer toutes les questions du QCM
        $questions = $qcm->getQuestions();
    
        // Traiter la soumission de chaque formulaire de question
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
    
                // Créer une nouvelle instance de StudentAnswer
                $studentAnswer = new StudentAnswer();
                $studentAnswer->setQcmId($qcm);
                $studentAnswer->setQuestionid($question);
                $studentAnswer->setUserid($this->getUser());
    
                // Enregistrer l'instance de StudentAnswer dans la base de données
                $em->persist($studentAnswer);
                $em->flush();
            }
        }
    
        // Rediriger l'utilisateur vers la page de résultats
        return $this->redirectToRoute('app_qcm_results', ['id' => $qcm->getId()]);
    }
    
    #[Route("/qcm/{id}/start", name:"app_qcm_start")]
    public function start(Request $request, Qcm $qcm): Response
    {

        dump($request->query->all());
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
            // Traitez la soumission du formulaire et enregistrez la réponse
            $this->entityManager->persist($studentAnswer);
            $this->entityManager->flush();
    
            // Redirigez vers la question suivante ou les résultats
            if ($questionIndex < $totalQuestions - 1) {
                return $this->redirectToRoute('app_qcm_start', ['id' => $qcm->getId(), 'question' => $questionIndex + 1]);
            } else {

                return $this->redirectToRoute('app_qcm_results', ['id' => $qcm->getId()]);
            }
        }
    
        return $this->render('qcm/question.html.twig', [
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
        $studentAnswers = $this->entityManager->getRepository(StudentAnswer::class)->findBy(['Qcm_id' => $qcm]);
    
        // Afficher les réponses des étudiants avec dump()
        dump($studentAnswers);
    
        $score = 0;
        $totalQuestions = count($qcm->getQuestions());
    
        foreach ($studentAnswers as $studentAnswer) {
            if ($studentAnswer->getAnswersId()->isIsTrue()) {
                $score++;
            }
        }
    
        // Afficher le score avec dump()
        dump($score);
    
        return $this->render('qcm/results.html.twig', [
            'qcm' => $qcm,
            'score' => $score,
            'totalQuestions' => $totalQuestions,
            'studentAnswers' => $studentAnswers,
        ]);
    }
    
    public function result(Request $request, QCM $qcm, EntityManagerInterface $entityManager)
    {
        // Récupérer les données soumises par le formulaire
        $submittedData = $request->request->all();
    
        // Créer une instance de StudentAnswer pour chaque réponse soumise
        foreach ($submittedData as $questionId => $answerId) {
            // Récupérer les entités correspondantes à l'ID de la question et de la réponse
            $question = $entityManager->getRepository(Question::class)->find($questionId);
            $answer = $entityManager->getRepository(Answers::class)->find($answerId);
    
            // Créer une nouvelle instance de StudentAnswer et la remplir
            $studentAnswer = new StudentAnswer();
            $studentAnswer->setUserId($this->getUser()); // À adapter selon votre logique d'utilisateur connecté
            $studentAnswer->setQcmId($qcm);
            $studentAnswer->setQuestionId($question);
            $studentAnswer->setAnswersId($answer);
            $studentAnswer->setResult(null); // À adapter selon vos besoins
    
            // Enregistrer l'entité StudentAnswer dans la base de données
            $entityManager->persist($studentAnswer);
        }
    
        // Flush pour enregistrer toutes les instances de StudentAnswer
        $entityManager->flush();
    
        // Calculer le pourcentage après avoir sauvegardé les réponses
        $score = 0;
        $totalQuestions = count($qcm->getQuestions());
    
        // Calcule le score de l'utilisateur
        foreach ($qcm->getQuestions() as $question) {
            $correctAnswerId = null;
            foreach ($question->getAnswers() as $answer) {
                if ($answer->isIsTrue()) {
                    $correctAnswerId = $answer->getId();
                    break;
                }
            }
    
            if (isset($submittedData[$question->getId()]) && $submittedData[$question->getId()] == $correctAnswerId) {
                $score++;
            }
        }
    
        $percentage = ($score / $totalQuestions) * 100;
    
        // Redirection vers une autre page après traitement
        return $this->redirectToRoute('app_qcm_results', ['id' => $qcm->getId(), 'percentage' => $percentage]);
    }    
}    