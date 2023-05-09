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
}
