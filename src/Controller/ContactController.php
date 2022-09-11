<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(EntityManagerInterface $entityManager, Request $request, SendMailService $mail): Response
    {
        $contact = new Contact();

        if($this->getUser()){
            $contact->setName($this->getUser()->getName())
                    ->setFirstname($this->getUser()->getFirstname())
                    ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $mail->send(
                'no-reply@monsite.fr',
                $contact->getEmail(),
                'Envoie du formulaire de contact au Gîte de la Régordane',
                'contact_client',
                compact('contact')
            );
    
            $mail->send(
                $contact->getEmail(),
                'no-reply@monsite.fr',
                'Demande Client',
                'contact_admin',
                compact('contact')
            );

            $this->addFlash('success', 'Le formulaire de contact a été envoyé avec succès');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
