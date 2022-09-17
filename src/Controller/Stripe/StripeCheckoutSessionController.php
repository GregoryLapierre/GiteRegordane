<?php

namespace App\Controller\Stripe;

use Stripe\Stripe;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/create-checkout-session/{id}', name: 'app_stripe_checkout_session')]
    public function index(Reservation $reservation): Response
    {
        /**
         * @var App\Entity\User $user
         */
        $user = $this->getUser();

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_TEST']);

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'client_reference_id' => $user->getId(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => 'Acompte pour réservation du ' . $reservation->getStartDate()->format('d/m/Y') . ' au ' . $reservation->getEndDate()->format('d/m/Y'),
                    ],
                    'unit_amount' => $reservation->getDeposit() * 100
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'reservationId' => $reservation->getId()
            ],
            'success_url' => $this->generateUrl('app_payment_success', ['id' => $reservation->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_payment_cancel', ['id' => $reservation->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        return $this->redirect($checkout_session->url, 303);
    }
}
