<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\PseudoTypes\False_;
use PhpParser\Node\Expr\Yield_;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username', 'Nom d\'utilisateur');
        yield TextField::new('nom');
        yield TextField::new('prenom');
        yield EmailField::new('email');
        yield ChoiceField::new('roles')
        ->allowMultipleChoices()
        ->renderAsBadges([
            'ROLE_ADMIN' => 'success',
            'ROLE_STUDENT' => 'warning'
        ])
        ->setChoices([
            'Administrateur' => 'ROLE_ADMIN',
            'Student' => 'ROLE_STUDENT'
        ]);
        yield TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)->onlyOnForms()->setRequired(False);
    }
    
}
