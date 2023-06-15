<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Coordinate;
use App\Entity\LigneDeCommande;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\LivraisonType;
use App\Form\OrderType;
use App\Repository\CommandeRepository;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class OrderController extends AbstractController
{

    private $entityManager;
    private $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository)
    {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request)
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request)
    {

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $orderD = [];
        $form->handleRequest($request);
        $order = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();

            // Enregistrer ma commande Order()
            $order = new Commande();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setStatus('processing...');


            $this->entityManager->persist($order);
            // Enregistrer Coordinate
            $coordinate = new Coordinate();
            $coordinate->setUser(($this->getUser()));
            $data= $form->getData();
            $coordinate->setAddress($data['address']);
            $coordinate->setFirstname($data['firstname']);
            $coordinate->setLastname($data['lastname']);
            $coordinate->setNumber($data['number']);
            $coordinate->setPays($data['pays']);
            $coordinate->setZipcode($data['zipcode']);
            $coordinate->setLivraison($data['livraison']);
            $this->entityManager->persist($coordinate);

            // Enregistrer mes produits OrderDetails()
            foreach ($cart->getFull() as $product) {
                $orderDetails = new LigneDeCommande();
                $orderDetails->setCommande($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantite($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();



        }

        return $this->redirect('/checkout');
    }


    #[Route('/commandes', name: 'orders_list')]
    public function afficher(): Response
    {

        $commandes = $this->commandeRepository->findAll();
        return $this->render('cart/admin.html.twig', [
            'orders' => $commandes
        ]);
    }

    #[Route('/user/orders', name: 'user_order_list')]
    public function userOrders(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('cart/user.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/update/order/{order}/{status}', name: 'order_status_update')]
    public function updateOrderStatus(Commande $order,$status): Response
    {
        $order->setStatus($status);
        $this->entityManager->persist($order);

        // actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'Your order status was updated'
        );
        return $this->redirectToRoute('orders_list');
    }


    /**
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/livraison', 'livraison.new')]
    public function new(
        SluggerInterface       $slugger,
        Request                $request,
        EntityManagerInterface $manager
    ): Response
    {
        $commande = new Commande();
        $form = $this->createForm(LivraisonType::class, $commande);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();
            $manager->persist($commande);
            $manager->flush();
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}