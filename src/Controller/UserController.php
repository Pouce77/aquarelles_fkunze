<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
}
