<?php
// src/Controller/DiscussionController.php
namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Message;
use App\Repository\DiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/discussion', name: 'app_discussion_list')]
    public function list(): Response
    {
        $discussions = $this->entityManager->getRepository(Discussion::class)->findAll();

        return $this->render('discussion/list.html.twig', [
            'controller_name' => 'DiscussionController',
            'discussions' => $discussions,
        ]);
    }


    #[Route('/discussion/{id}', name: 'app_discussion_show')]
    public function show(int $id): Response
    {
        // Récupérer la discussion ou le forum correspondant à l'id depuis la base de données
        $discussion = $this->entityManager->getRepository(Discussion::class)->find($id);

        if (!$discussion) {
            throw $this->createNotFoundException('Discussion not found');
        }

        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }
    #[Route('/discussion/{id}/add-message', name: 'app_discussion_add_message')]
    public function addMessage(Discussion $discussion, Request $request): Response
    {
        // Créer un nouveau message
        $message = new Message();
        
        // Récupérer le contenu du message depuis le formulaire
        $content = $request->request->get('content');

        // Vérifier si le contenu est valide
        if ($content) {
            // Ajouter le contenu au message
            $message->setContent($content);
            
            // Ajouter le message à la discussion
            $discussion->addMessage($message);
            
            // Enregistrer les modifications en base de données
            $this->entityManager->flush();
            
            // Rediriger vers la page de la discussion
            return $this->redirectToRoute('app_discussion_show', ['id' => $discussion->getId()]);
        }

        // En cas de contenu invalide, afficher un message d'erreur
        $this->addFlash('error', 'Veuillez saisir un contenu valide.');

        // Rediriger vers la page de la discussion
        return $this->redirectToRoute('app_discussion_show', ['id' => $discussion->getId()]);
    }
    #[Route('/discussion/search', name: 'app_discussion_search')]
    public function search(Request $request, DiscussionRepository $discussionRepository): Response
    {
        $query = $request->query->get('query');
    
        // Créer une requête SQL personnalisée à l'aide du QueryBuilder
        $qb = $discussionRepository->createQueryBuilder('d');
    
        // Ajouter une clause WHERE pour filtrer les discussions en fonction de la requête de recherche
        $qb->where($qb->expr()->like('d.titre', ':query'))
            ->setParameter('query', '%'.$query.'%');
    
        // Récupérer les résultats de la requête
        $discussions = $qb->getQuery()->getResult();
    
        // Passer les variables au modèle Twig
        return $this->render('discussion/search.html.twig', [
            'query' => $query,
            'discussions' => $discussions,
        ]);
    }
    

}
