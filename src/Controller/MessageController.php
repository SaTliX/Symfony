<?php
// src/Controller/MessageController.php
namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/message/create/{discussionId}', name: 'app_message_create', methods: ['POST'])]
    public function create(Request $request, int $discussionId): Response
    {
        $discussion = $this->entityManager->getRepository(Discussion::class)->find($discussionId);

        if (!$discussion) {
            throw $this->createNotFoundException('Discussion not found');
        }

        $content = $request->request->get('content');
        if (!$content) {
         $this->addFlash('error', 'Le message ne peut pas être vide');
         return $this->redirectToRoute('app_discussion_show', ['id' => $discussionId]);
        }

        $message = new Message($this->getUser());
        $message->setContent($content);
        $message->setDiscussion($discussion); // Définit la discussion principale
        $message->setDiscussionMessage($discussion); // Définit la discussion du message
        $message->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        // Rediriger l'utilisateur vers la page de la discussion après avoir ajouté le message
        return $this->redirectToRoute('app_discussion_show', ['id' => $discussionId]);
    }
}

