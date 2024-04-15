<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Media;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 * @method User getUser()
 */
class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        
        Field::new('pdfFile')->setFormType(VichFileType::class)->onlyOnForms();


    }
}

