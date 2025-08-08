<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\CourierProfile;
use App\Entity\Order;
use App\Entity\PriceRule;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Cargo Platform');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Couriers', 'fa fa-id-card', CourierProfile::class);
        yield MenuItem::linkToCrud('Orders', 'fa fa-box', Order::class);
        yield MenuItem::linkToCrud('Price Rules', 'fa fa-money-bill', PriceRule::class);
        yield MenuItem::linkToRoute('Vue Admin SPA', 'fa fa-bolt', 'admin_spa');
    }
}