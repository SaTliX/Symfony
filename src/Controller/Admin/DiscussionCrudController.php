<?php

namespace App\Controller\Admin;

use App\Entity\Discussion;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class DiscussionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Discussion::class;
    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('titre');
        yield DateTimeField::new('createdAt')->hideOnForm();
    }

}
