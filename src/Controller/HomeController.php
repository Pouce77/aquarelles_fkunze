<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Form\ContactType;
use App\Repository\ActualityRepository;
use App\Repository\PaintingRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActualityRepository $actualityRepository): Response
    {
        $actualities=$actualityRepository->findAll();
        $actuality1=end($actualities);
        if($actuality1){
        $actuality2=$actualities[array_search($actuality1,$actualities)-1];
        $actuality=[$actuality1,$actuality2];
        }

        return $this->render('home/index.html.twig', [
            "actuality" => $actuality
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

    #[Route('/gallery/dessins', name: 'app_gallery_dessin')]
    public function galleryDessin(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findAll(); // SELECT * FROM `painting`;

        return $this->render('home/gallery.html.twig', [
            "paintings"=>$paintings,
        ]);
    }

    #[Route('/gallery/videos', name: 'app_gallery_videos')]
    public function galleryVideo(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findAll(); // SELECT * FROM `painting`;

        return $this->render('home/gallery.html.twig', [
            "paintings"=>$paintings,
        ]);
    }
    
    #[Route('/contact', name: 'app_contact')]
    public function contact(HttpFoundationRequest $request): Response
    {
       $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
                      
            return $this->redirectToRoute("app_home");
        }

        return $this->render("home/contact.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findAll(); // SELECT * FROM `painting`;

        return $this->render('home/presentation.html.twig', [
            "paintings"=>$paintings,
        ]);
    }
}
