<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            
        ]);
    }

    #[Route('/gallery', name: 'app_gallery')]
    public function gallery(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findAll(); // SELECT * FROM `painting`;

        return $this->render('home/gallery.html.twig', [
            "paintings"=>$paintings,
        ]);
    }    
}
