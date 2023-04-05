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
        ->add('username',TextType::class,[
            "label" => "Votre nom",
            "required" => true,
            'constraints' => [new Length(['min' => 2, 'max' => 150,'minMessage' => 'La taille minimale est de 2 caractères !','maxMessage' => 'La taille maximum est de 150 caractères !'])],
            'constraints' => [new NotBlank(['message' => 'Le nom ne peut pas être vide !'])],
        ])

        ->add('email',EmailType::class,[
            "label" => "Votre email",
            "required" => true,
            'constraints' => [new Length(['min' => 2, 'max' => 150,'minMessage' => 'La taille minimale est de 2 caractères !','maxMessage' => 'La taille maximum est de 150 caractères !'])],
            'constraints' => [new Email(['message' => 'Le mail n\'est pas valide'])]
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
