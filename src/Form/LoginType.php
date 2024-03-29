<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
          ->add('email',EmailType::class,[
            "label" => "Email",
            "attr" => ["class" => "form-control", "placeholder" => "Votre Email"],
            "required" => true,
            //'row_attr' => ['class' => 'nom', 'id' => 'name'],
            "constraints" => [
              new Length(["min" => 2, "max" => 180, "minMessage" => "L'email ne doit pas faire moins de 2 caractères", "maxMessage" => "L'email' ne doit pas faire plus de 180 caractères"]),
              new NotBlank(["message" => "L'email' ne doit pas être vide !"])
              ]
          ])
          ->add('password',PasswordType::class,[
            "label"=>"Mot de passe",
            "attr" => ["class" => "form-control"],
            "required" => true,
            'constraints' => [
              new NotBlank(["message" => "Le mot de passe ne peut pas être vide !"])
              ]
          ]);
        
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      "data_class" => User::class,
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id'   => 'user_item'
    ]);
  }

}