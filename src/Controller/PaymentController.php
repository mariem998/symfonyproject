<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneDeCommande;
use App\Entity\Product;
use App\Security\EmailVerifier;
use App\Service\Cart;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelpperInterface;


class PaymentController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
        // On envoie un mail

    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/checkout', name: 'checkout')]
    public function checkout($stripeSK,Cart $cart,ManagerRegistry $doctrine): Response
    {
        $stripe = new StripeClient($stripeSK);
        $cart = $cart->getFull();
        $lineItems = array();
        foreach ( $cart as $row){
            $lineItems[] = array(
                'price_data' => array(
                    'currency' => 'USD',
                    'product_data' => array(
                        'name' => $row['product']->getName(),
                    ),
                    'unit_amount' => $row['product']->getPrice()*100,
                ),
                'quantity' => $row['quantity'],
            );
        }
        $session = $stripe->checkout->sessions->create([

            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success-url', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel-url', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        //return $response->withHeader('location',$session->url)->withStatus(303);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success-url')]
    public function successUrl(): Response
    {
        $user = $this->getUser();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('symfonyprojet7@gmail.com', 'Shop'))
                ->to($user->getEmail())
                ->subject('Payment confirmation')
                ->htmlTemplate('payment/confirmationPayment.html.twig')
        );
        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel-url', name: 'cancel-url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
