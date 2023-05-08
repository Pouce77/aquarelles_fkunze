<?php

namespace App\Controller\Admin;

use App\Entity\Painting;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaintingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Painting::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Titre'),
            TextareaField::new('description')->setLabel('Description'),
            TextareaField::new('technic')->setLabel('Technique'),
            ImageField::new('image')
                ->setBasePath('css/images')
                ->setUploadDir('public/css/images')
                ->setLabel('Image'),
            ChoiceField::new('Category')
            ->setChoices([
                'Aquarelle' => 'Aquarelle',
                'Dessin' => 'Dessin'
            ])
            ->setLabel('Catégorie') 
        ];
    }
    
}
