<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Painting;
use App\Form\CommentType;
use App\Form\PaintType;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
                $originalFilename=null;
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
                    $painting->setImage($newFilename);
                }
    
                $em = $doctrine->getManager();
                $em->persist($painting);
                $em->flush();
                return $this->redirectToRoute("app_home");
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

        return $this->redirectToRoute('app_admin_oeuvres');
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
           return $this->redirectToRoute('app_admin_oeuvres');
        }

        return $this->render("painting/painting.html.twig", [
            "form" => $form->createView(),
            "route" => ""
        ]);
    }

    #[Route('/painting/view/{id<\d+>}', name: 'app_painting_view')]
    public function paintingView(MailerInterface $mailer, CommentRepository $commentRepository,Painting $painting,HttpFoundationRequest $request,ManagerRegistry $doctrine): Response
    {
        //On récupère les commentaires du tableau
        $id=$painting->getId();
        $comments=$commentRepository->findBy([
            "painting"=>$id
                ]);
        dump($comments);
        //On crée un nouveau commmentaire si le formulaire est rempli
        $comment=new Comment();
        $form=$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $date=new DateTimeImmutable();
            $date->format("d/m/Y");
            $comment->setPublishedAt($date);
            $comment->setPainting($painting);
            $comment->setValidate(false);
            $entityManager=$doctrine->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $email = ((new TemplatedEmail()))
            ->from('julienkunze@free.fr')
            ->to('julienkunze0@gmail.com')
            ->subject('Nouveau commentaire sur les aquarelles de François Kunzé')
            ->htmlTemplate('email/newComment.html.twig')
            ->context([
                'oeuvre' => $comment->getPainting()->getName(),
                'comment' => $comment->getContent(),
                'name' => $comment->getUser()
            ])
            ;

            $mailer->send($email);

           return $this->redirectToRoute('app_painting_view',['id' => $painting->getId()]);
        }

        return $this->render('painting/paintingView.html.twig', [
            "painting"=>$painting,
            "comments"=>$comments,
            "form" => $form->createView()
        ]);
    }

    #[Route('/comment/validate/{id<\d+>}', name: 'app_comment_validate')]
    public function validate(Comment $comment,ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $validation=$comment->getValidate();
        $comment->setValidate(!$validation);
        $entityManager=$doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
            
    return new Response;
    }
}