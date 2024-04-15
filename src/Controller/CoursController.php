<?php
namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CoursController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/cours/{slug}', name: 'cours_show')]
    public function show(CoursRepository $coursRepository, string $slug): Response
    {
        $cours = $coursRepository->findOneBy(['slug' => $slug]);

        if (!$cours) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('cours/show.html.twig', [
            'controller_name' => 'CoursController',
            'cours' => $cours,
        ]);
    }

    #[Route('/cours', name: 'app_cours_list')]
    public function list(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();
        $leftCol = 5;
        $rightCol = 7;

        return $this->render('cours/list2.html.twig', [
            'controller_name' => 'CoursController',
            'cours' => $cours,
            'leftCol' => $leftCol,
            'rightCol' => $rightCol,
        ]);
    }

    #[Route("/upload-pdf", name: "upload_pdf", methods: ["POST"])]
    public function uploadPdf(Request $request, UploaderHelper $uploaderHelper): Response
    {
        $pdfFile = $request->files->get('pdf_file');

        if (!$pdfFile) {
            // Handle the case when no file is uploaded
            // Return a JSON response or a redirection with an error message
        }

        $coursId = $request->request->get('cours_id');
        $cours = $this->entityManager->getRepository(Cours::class)->find($coursId);

        if (!$cours) {
            // Handle the case when the Cours entity is not found
        }

        $cours->setPdfFile($pdfFile);
        $this->entityManager->flush();

        $pdfFileUrl = $uploaderHelper->asset($cours, 'pdfFile');

        return $this->json([
            'message' => 'PDF file uploaded successfully',
            'pdf_file_url' => $pdfFileUrl,
        ]);
    }

    #[Route('/cours/{id}', name: 'programme_cours')]
    public function programmeCours($id, CoursRepository $coursRepository): BinaryFileResponse
    {
        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }

        // Récupérer le chemin absolu vers le fichier PDF
        $pdfFilePath = $this->getParameter('pdf_directory').'/'.$cours->getPdfFilename();

        // Créer une BinaryFileResponse pour envoyer le fichier au navigateur
        $response = new BinaryFileResponse($pdfFilePath);
        
        // Définir le nom du fichier à télécharger
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $cours->getPdfFilename()
        );

        return $response;
    }
}
