<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Email',EmailType::class,[
            "label" => "Email",
            "required" => true,
            'constraints' => [new Length(['min' => 0, 'max' => 150,'minMessage' => 'L\'email ne peut être vide !','maxMessage' => 'La taille maximum est de 150 caractères !'])],
            'constraints' => [new Email(['message'=>'Vous devez entrer un email valide.'])]
         
            ])
         
          ->add('message',TextareaType::class,[
            "label"=>"Votre message",
            "required" => true,
            'constraints' => [new NotBlank(['message' => 'Le contenu du message ne peut pas être vide !'])],
            'constraints' => [new Length(['min' => 5, 'max' => 400,'minMessage' => 'La taille du contenu est comprise entre 5 et 400 caractères !','maxMessage' => 'La taille du contenu est comprise entre 5 et 320 caractères !'])]

          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
