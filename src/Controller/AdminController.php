<?php

namespace App\Controller;

use App\Repository\ActualityRepository;
use App\Repository\CommentRepository;
use App\Repository\PaintingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");        
        return $this->render('admin/admin.html.twig');
    }

    #[Route('/admin/oeuvres', name: 'app_admin_oeuvres')]
    public function oeuvres(PaintingRepository $paintingRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        $paintings=$paintingRepository->findAll();
       
        return $this->render('admin/oeuvres.html.twig', [
            
            "paintings" => $paintings,
        ]);
    }

    #[Route('/admin/actualities', name: 'app_admin_actualities')]
    public function actualities(ActualityRepository $actualityRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        $actualities=$actualityRepository->findAll();
       
        return $this->render('admin/actualities.html.twig', [
            
            "actualities" => $actualities,
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        $users=$userRepository->findAll();
       
        return $this->render('admin/users.html.twig', [
            
            "users" => $users,
        ]);
    }
}
