<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Coordinate;
use App\Entity\LigneDeCommande;
use App\Form\OrderType;
use App\Repository\CommandeRepository;
use App\Repository\ContactRepository;
use App\Repository\CoordinateRepository;
use App\Repository\LigneDeCommandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class DetailController extends AbstractController
{

    private $lignecommandeRepository;
    private $coordinateRepository;

    public function __construct(
        LigneDeCommandeRepository $lignecommandeRepository,
        CoordinateRepository $coordinateRepository,
        ManagerRegistry $doctrine)
    {
        $this->lignecommandeRepository = $lignecommandeRepository;
        $this->coordinateRepository = $coordinateRepository;
    }


    #[Route('/detail', name: 'detail_command', methods: ['GET'])]

    public function details_comande(LigneDeCommandeRepository  $repository): Response
    {
        $cmd = $repository->findAll();
        return $this->render('details/index.html.twig', [
            'detail' => $cmd
        ]);
    }



    #[Route('/detailadmin', name: 'detail_commandadmin', methods: ['GET'])]

    public function details_comandeadmin(LigneDeCommandeRepository  $repository, CoordinateRepository $coordinate): Response
    {
        $coord = $coordinate->findAll();
        $cmd = $repository->findAll();
        return $this->render('details/admin.html.twig', [
            'detail' => $cmd,
            'cor' => $coord
        ]);
    }



}
