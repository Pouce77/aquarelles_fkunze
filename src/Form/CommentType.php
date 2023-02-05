<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Painting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class CommentType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
          ->add("user", HiddenType::class, [
            "required" => true
          ])
          ->add('content',TextareaType::class,[
            "label"=>"Description",
            "required" => true,
            'constraints' => [new NotBlank(['message' => 'Le commentaire ne peut pas être vide !'])],
            'constraints' => [new Length(['min' => 5, 'max' => 320,'minMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !','maxMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !'])]
          ]);
        
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      "data_class" => Comment::class,
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id'   => 'post_item'
    ]);
  }

}