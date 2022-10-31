<?php

namespace App\Controller\Stripe;

use Stripe\Stripe;
use App\Service\SendMailService;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    #[Route('/stripe/webhook', name: 'app_stripe_webhook')]
    public function index(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository,  SendMailService $mail, UserRepository $userRepository): Response
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_TEST']);

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_deb5921749d7a15e84957bf279b6b777404077f905fef5b420a9d0edb3a966e4';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            // return $this->json('Erreur', 400);
            return $this->render('bundles/TwigBundle/Exception/error.html.twig');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            // return $this->json('Erreur', 400);
            return $this->render('bundles/TwigBundle/Exception/error.html.twig');
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $idReservation = $event->data->object['metadata']['reservationId'];
             
                $reservation = $reservationRepository->find($idReservation);
                $reservation->setStatus('Paiement accepté');
                
                $entityManager->persist($reservation);
                $entityManager->flush();

                $userId = $event->data->object['client_reference_id'];
                $user = $userRepository->find($userId);
                $email = $event->data->object['customer_email'];
                
                $mail->send(
                    'no-reply@monsite.fr',
                    $email,
                    'Validation de la réservation sur Gîte de la Régordane',
                    'validate_reservation',
                    compact('user', 'reservation')
                );
                
                // return $this->json($event->data->object, 200);
                return $this->json('Paiement enregistré', 200);
            default:
                return $this->json('Evenement non pris en charge', 200);
        }
      
        return $this->json('Ok', 200);
    }
}
