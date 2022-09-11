<?php

namespace App\Controller\Stripe;

use App\Entity\Reservation;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/stripesuccesspayment/{id}', name: 'app_payment_success')]
    public function success(Reservation $reservation, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        /**
         * @var App\Entity\User $user
         */
        $user = $this->getUser();
        $reservation->setStatus('Paiement accepté');
        
        $entityManager->persist($reservation);
        $entityManager->flush();
        
        $mail->send(
            'no-reply@monsite.fr',
            $user->getEmail(),
            'Validation de la réservation sur Gîte de la Régordane',
            'validate_reservation',
            compact('user', 'reservation')
        );

        $this->addFlash('success', 'Votre paiement est accepté');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/stripecancelpayment/{id}', name: 'app_payment_cancel')]
    public function cancel(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatus('Paiement refusé');
        
        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->addFlash('danger', 'Votre paiement est refusé');
        return $this->redirectToRoute('app_home');
    }
}
