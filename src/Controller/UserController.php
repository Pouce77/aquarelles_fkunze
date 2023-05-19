<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $user=new User($userPasswordHasher);
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {    
            $entityManager=$doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/form.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id<\d+>}', name: 'app_user_delete')]
    public function deleteUser(User $user, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em=$doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/user/update/{id<\d+>}', name: 'app_user_update')]
    public function updateUser(HttpFoundationRequest $request,User $user, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $array=array();
        $roles=$request->request->all();

        foreach ($roles as $role=>$on) {
            
            if ( $role=="Administrateur" ){
                array_push($array,'ROLE_ADMIN');
            }elseif ($role=="Utilisateur") {
                array_push($array,'ROLE_USER');
            }
        }
            
        $user->setRoles($array);
        $entityManager=$doctrine->getManager();
        $entityManager->flush();

           return $this->redirectToRoute('app_admin_users');
       
    }

    #[Route('/user/oubli-pass', name:'forgotten_password')]
    public function forgotPassword(
        HttpFoundationRequest $request, 
        MailerInterface $mailer,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        ): Response
    {
        $form=$this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user=$userRepository->findOneByEmail($form->get('email')->getData());
          
            if ($user)
            {
            $token=$tokenGenerator->generateToken();
            $user->setResetToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            $url=$this->generateUrl('reset_password',['token' =>$token],UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
            ->from('julienkunze@free.fr')
            ->to($form->get('email')->getData())
            ->subject('Réinitialisation de votre mot de passe sur le site des aquarelles de François Kunzé')
            ->htmlTemplate('email/resetPasswordRequestEmail.html.twig')
            ->context([
                'url' => $url
            ]);
            }

            $mailer->send($email);

            return $this->render('security/mailSuccess.html.twig',[
                'formReset'=>$form
            ]);
            
        }

        return $this->render('security/resetPasswordRequest.html.twig',[
            'formReset'=>$form
        ]);
    }

    #[Route('/user/reset-pass/{token}', name:'reset_password')]
    public function resetPassword(
        string $token,
        EntityManagerInterface $entityManager,
        HttpFoundationRequest $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
        ):Response
    {
        $user=$userRepository->findOneByResetToken($token);
        $user->setPasswordHasher($passwordHasher);
        
        $form=$this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            
            $user->setResetToken('');
            $user->setPassword($form->get('password')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('security/passwordNew.html.twig');
        }

        return $this->render('security/resetPassword.html.twig',[
            'formResetPass' => $form
        ]);

    }

}
