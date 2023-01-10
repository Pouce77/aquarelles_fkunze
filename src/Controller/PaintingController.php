<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Form\PaintType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\String\Slugger\SluggerInterface;

class PaintingController extends AbstractController
{
    #[Route('/painting/add', name: 'app_painting_add')]
    public function create(HttpFoundationRequest $request, ManagerRegistry $doctrine,SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $painting=new Painting();
        $form=$this->createForm(PaintType::class, $painting);
        $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $image = $form->get('image')->getData();
    
                if ($image) {
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                    try {
                        $image->move(
                            $this->getParameter('uploads'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        dump($e);
                    }
                    $painting->setImage('/public/css/images/'.$image);
                }
    
                $em = $doctrine->getManager();
                $em->persist($painting);
                $em->flush();
                return $this->redirectToRoute("app_gallery");
            }
    
        return $this->render('painting/painting.html.twig', [
            "form" => $form->createView(),
            "route" => "/painting/add"
        ]);
    }

    #[Route('/painting/delete/{id<\d+>}', name: 'app_painting_delete')]
    public function delete(Painting $painting, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $image=$painting->getImage();
        if ($image){
            $nomImage=$this->getParameter('uploads').'/'.$image;
            if(file_exists($nomImage)){
                unlink($nomImage);
            }
        }
        $em=$doctrine->getManager();
        $em->remove($painting);
        $em->flush();

        return $this->redirectToRoute('app_gallery');
    }

    #[Route('/painting/update/{id<\d+>}', name: 'app_painting_update')]
    public function update(HttpFoundationRequest $request,Painting $painting, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form=$this->createForm(PaintType::class, $painting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           $entityManager=$doctrine->getManager();
           $entityManager->flush();
           return $this->redirectToRoute('app_gallery');
        }

        return $this->render("painting/painting.html.twig", [
            "form" => $form->createView(),
            "route" => ""
        ]);
    }
}