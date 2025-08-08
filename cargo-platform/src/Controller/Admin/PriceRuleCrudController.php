<?php

namespace App\Controller\Admin;

use App\Entity\PriceRule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceRuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceRule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('minWeightKg'),
            IntegerField::new('maxWeightKg'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('currency'),
            TextField::new('direction'),
            BooleanField::new('active'),
        ];
    }
}