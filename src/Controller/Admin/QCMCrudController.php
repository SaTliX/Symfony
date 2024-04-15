<?php

namespace App\Controller\Admin;

use App\Entity\QCM;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class QCMCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QCM::class;
    }

    public function configureFields(string $pageName): iterable
    {
   
        yield TextField::new('title');
        yield TextEditorField::new('description');
        yield TimeField::new('Time')->setFormat('i:s'); // Afficher uniquement les minutes et les secondes
 
    }
}
