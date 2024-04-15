<?php

namespace App\Controller\Admin;

use App\Entity\Answers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class AnswersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Answers::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('label', 'Libellé');
        yield BooleanField::new('isTrue', 'Est Vraie');
        yield AssociationField::new('id_question', 'Question');


        // L'association à la question peut être gérée depuis le CRUD de la question
    }
}

