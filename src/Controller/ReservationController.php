<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->get('start_date')->getViewData() <= $form->get('end_date')->getViewData() && $form->isSubmitted() && $form->isValid()) {
            $dateIn = $form->get('start_date')->getViewData();
            $dateOut = $form->get('end_date')->getViewData();
            $number_adult = $form->get('number_person')->getViewData();

            $date1 = new DateTime($dateIn);
            $date2 = $date1->diff(new DateTime($dateOut));
            $nbr_day = $date2->days + 1;
            $tourismTax = ($nbr_day - 1) * $number_adult * 0.6;
            $totalTax = $tourismTax + ($tourismTax / 10);
            $price = round((($nbr_day * 60) + $totalTax), 2);
            $deposit = round(($price * 0.3), 2);

            $reservation->setUser($this->getUser())
                        ->setStatus('En attente de paiement')
                        ->setPrice($price)
                        ->setDeposit($deposit);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_stripe_checkout_session', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/index.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }
}
