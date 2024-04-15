<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Doctrine\ORM\EntityManagerInterface;


class CoursCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Cours::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('content'),
            TextEditorField::new('shortDescription'),
            TextField::new('featuredText'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            AssociationField::new('categories'),
            AssociationField::new('featuredImage')->hideOnIndex(),
            Field::new('pdfFile')->setFormType(VichFileType::class)->onlyOnForms(),
            Field::new('recommended')->setFormTypeOptions(['required' => false]),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        $updateRecommendedAction = Action::new('updateRecommended', 'Mettre à jour le statut recommandé')
            ->linkToCrudAction('updateRecommendedStatus');

        return $actions->add(Crud::PAGE_EDIT, $updateRecommendedAction);
    }

    // Nouvelle méthode pour gérer l'action de mise à jour du statut recommandé
    public function updateRecommendedStatus(Request $request): Response
    {
        // Récupérer l'ID du cours depuis la requête
        $coursId = $request->query->get('entityId');
        // Récupérer le statut recommandé depuis la requête
        $isRecommended = $request->query->get('isRecommended');

        // Récupérer le cours depuis la base de données
        $cours = $this->entityManager->getRepository(Cours::class)->find($coursId);

        if (!$cours) {
            throw $this->createNotFoundException('Le cours n\'existe pas.');
        }

        // Mettre à jour le statut recommandé du cours
        $cours->setRecommended($isRecommended);
        $this->entityManager->persist($cours);
        $this->entityManager->flush();

        // Rediriger ou retourner une réponse selon votre logique
        return $this->redirectToRoute('admin_app_cours_edit', ['id' => $coursId]);
    }
}