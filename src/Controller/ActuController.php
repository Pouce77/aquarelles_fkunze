<?php

namespace App\Controller;

use App\Entity\Actuality;
use App\Form\ActuType;
use App\Repository\ActualityRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActuController extends AbstractController
{

    #[Route('/actuality', name: 'app_actuality')]
    public function actuality(ActualityRepository $actualityRepository): Response
    {

        $actualities = $actualityRepository->findAll(); // SELECT * FROM `actuality`;
        return $this->render("home/actualite.html.twig", [
           "actualities"=>$actualities
        ]);

    }

    
    #[Route('/actuality/add', name: 'app_actuality_add')]
    public function addActu(HttpFoundationRequest $request,ManagerRegistry $doctrine,SluggerInterface $slugger): Response
    {
    
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $actuality=new Actuality();
        $form=$this->createForm(ActuType::class, $actuality);
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
                    $actuality->setImage($newFilename);
        
                }

                $date=new DateTimeImmutable();
                $date->format("d/m/Y");
                $actuality->setPublishedAt($date);

                $em = $doctrine->getManager();
                $em->persist($actuality);
                $em->flush();
                return $this->redirectToRoute("app_home");
            }
    
        
        return $this->render("actu/addactu.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/actuality/delete/{id<\d+>}', name: 'app_actuality_delete')]
    public function delete(Actuality $actuality, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em=$doctrine->getManager();
        $em->remove($actuality);
        $em->flush();

        return $this->redirectToRoute('app_admin_actualities');
    }

    #[Route('/actuality/update/{id<\d+>}', name: 'app_actuality_update')]
    public function update(HttpFoundationRequest $request,Actuality $actuality, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form=$this->createForm(ActuType::class, $actuality);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           $entityManager=$doctrine->getManager();
           $entityManager->flush();
           return $this->redirectToRoute('app_admin_actualities');
        }

        return $this->render("actu/addactu.html.twig", [
            "form" => $form->createView()
        ]);
    }

}
