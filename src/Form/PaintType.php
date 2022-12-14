<?php

namespace App\Form;

use App\Entity\Painting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class PaintType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
          ->add('name',TextType::class,[
            "label" => "Titre",
            "required" => false,
            'constraints' => [new Length(['min' => 0, 'max' => 150,'minMessage' => 'Le titre ne peut être vide !','maxMessage' => 'La taille maximum est de 150 caractères !'])]
          ])
          ->add('description',TextareaType::class,[
            "label"=>"Description",
            "required" => true,
            'constraints' => [new NotBlank(['message' => 'La description ne peut pas être vide !'])],
            'constraints' => [new Length(['min' => 5, 'max' => 320,'minMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !','maxMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !'])]
          ])
          ->add('technic',TextareaType::class,[
            "label"=>"Technique",
            "required" => true,
            'constraints' => [new NotBlank(['message' => 'La technique ne peut pas être vide !'])],
            'constraints' => [new Length(['min' => 5, 'max' => 320,'minMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !','maxMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !'])]
          ])   
          ->add("image", FileType::class, [
            "label" => "L'image",
            'mapped' => false,
            "required" => false,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        "image/gif",
                        "image/png",
                        "image/svg+xml",
                        "image/jpg",
                        "image/webp"
                    ],
                    'mimeTypesMessage' => 'Veuillez proposer une image valide.',
                ])
            ],
        ]);
        
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      "data_class" => Painting::class,
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id'   => 'post_item'
    ]);
  }

}