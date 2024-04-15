<?php

namespace App\Controller\Admin;

use App\Entity\Answers;
use App\Entity\Cours;
use App\Entity\Categorie;
use App\Entity\Discussion;
use App\Entity\QCM;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(CoursCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard');
            
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour', 'fa fa-undo', 'app_home');
        
        yield MenuItem::subMenu('Cours', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Tous les cours', 'fas fa-newspaper', Cours::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Cours::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Catégories', 'fas fa-newspaper', Categorie::class),

        ]);

        yield MenuItem::subMenu('Forum', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Tous les forums', 'fas fa-newspaper', Discussion::class),
            MenuItem::linkToCrud('Ajouter un forum', 'fas fa-plus', Discussion::class)->setAction(Crud::PAGE_NEW),
        ]);

        
        yield MenuItem::subMenu('User', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Tous les users', 'fas fa-user-friends', User::class),
            MenuItem::linkToCrud('Ajouter un user', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Médias', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('Médiathèque', 'fas fa-photo-video', Media::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Media::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Qcm', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('Qcm', 'fas fa-photo-video', QCM::class),
            MenuItem::linkToCrud('Question', 'fas fa-newspaper', Question::class),
            MenuItem::linkToCrud('Answers', 'fas fa-newspaper', Answers::class),


        ]);
    }
    
    
}
