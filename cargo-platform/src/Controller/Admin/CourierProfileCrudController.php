<?php

namespace App\Controller\Admin;

use App\Entity\CourierProfile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class CourierProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourierProfile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            ArrayField::new('routes'),
            ArrayField::new('travelDates'),
            IntegerField::new('capacityKg'),
            TextField::new('passportPath')->hideOnForm(),
            NumberField::new('rating'),
            NumberField::new('reliabilityScore'),
        ];
    }
}