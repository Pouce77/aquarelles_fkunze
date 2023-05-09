<?php

namespace App\Controller\Admin;

use App\Entity\Actuality;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActualityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actuality::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title')->setLabel('Titre'),
            TextareaField::new('content')->setLabel('Contenu'),
            ImageField::new('image')
                ->setBasePath('css/images')
                ->setUploadDir('public/css/images')
                ->setLabel('Image'),
            DateTimeField::new('publishedAt')->setLabel('Publié le')
        ];
    }
    
}
