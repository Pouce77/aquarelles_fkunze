<?php

namespace App\Controller;


use App\Form\ContactType;
use App\Repository\ActualityRepository;
use App\Repository\LinkRepository;
use App\Repository\PaintingRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActualityRepository $actualityRepository, PaintingRepository $paintingRepository): Response
    {
        $paintings=$paintingRepository->findAll();
        $actualities=$actualityRepository->findBy([],["id"=>"DESC"],2);

        return $this->render('home/index.html.twig', [
            "actuality" => $actualities,
            "paintings" => $paintings
        ]);
    }

    #[Route('/gallery/aquarelles', name: 'app_gallery_aquarelle')]
    public function gallery(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findby([
            "category" => "Aquarelle"
        ]); // SELECT * FROM `painting` WHERE `category` = `Aquarelle`
        return $this->render('home/gallery.html.twig', [
            "paintings"=>$paintings
        ]);
    }

    #[Route('/gallery/dessins', name: 'app_gallery_dessin')]
    public function galleryDessin(PaintingRepository $paintingRepository): Response
    {
        $paintings = $paintingRepository->findby([
            "category" => "Dessin"
        ]); // SELECT * FROM `painting` WHERE `category` = `Dessin`
        return $this->render('home/gallery.html.twig', [
            "paintings"=>$paintings
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
    public function contact(HttpFoundationRequest $request, MailerInterface $mailer): Response
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
            ->from('julienkunze@free.fr')
            ->to('julienkunze0@gmail.com')
            ->subject('Nouveau message sur les aquarelles de François Kunzé')
            ->text($request->request->all('contact')['message'])
            ->htmlTemplate('email/newContact.html.twig')
            ->context([
                'message' => $request->request->all('contact')['message'],
                'name' => $request->request->all('contact')['username'],
                'emailUser' => $request->request->all('contact')['email']
            ])
        ;

        try {
            $mailer->send($email);
            $this->addFlash('success','Votre message a été envoyé avec succés !');
            return $this->render("home/contact.html.twig",[
                'form'=>$form->createView()
            ]);
         } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            $this->addFlash('danger',"Une erreur s'est produite lors de l'envoi de votre message !");            
        }
        
        
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
            "paintings"=>$paintings
        ]);
    }

    #[Route('/liens', name: 'app_liens')]
    public function liens(LinkRepository $linkRepository): Response
    {
        $links=$linkRepository->findAll(); // SELECT * FROM `link`;

        return $this->render('home/liens.html.twig',[
            "links"=>$links
        ]);
    }
}
