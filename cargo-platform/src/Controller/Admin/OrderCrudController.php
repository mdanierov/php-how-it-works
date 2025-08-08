<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client'),
            AssociationField::new('assignedCourier'),
            IntegerField::new('weightKg'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('currency'),
            TextField::new('fromAddress'),
            TextField::new('toAddress'),
            TextField::new('direction'),
            ChoiceField::new('status')->setChoices([
                'pending' => 'pending',
                'awaiting_payment' => 'awaiting_payment',
                'assigned' => 'assigned',
                'paid' => 'paid',
                'cancelled' => 'cancelled',
            ]),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}