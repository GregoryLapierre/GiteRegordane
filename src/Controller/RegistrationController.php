<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            //Je génère le JWT de l'utilisateur
            //Je crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //Je crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            //Je génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            //J'envoie un mail
            $mail->send(
                'no-reply@monsite.fr',
                $user->getEmail(),
                'Activation de votre compte sur Gite de la Régordane',
                'register',
                compact('user', 'token')
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        //Je vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            //Je récupère le payload
            $payload = $jwt->getPayload($token);

            //Je récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            //Je vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);

                $this->addFlash('success', 'Le compte utilisateur est activé');
                return $this->redirectToRoute('app_home');
            }
        }
        //Si le token a un problème
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        /**
         * @var App\Entity\User $user
         */
        $user = $this->getUser();
       
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('app_home');
        }

        //Je génère le JWT de l'utilisateur
        //Je crée le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        //Je crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        //Je génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        //J'envoie un mail
        $mail->send(
            'no-reply@monsite.fr',
            $user->getEmail(),
            'Activation de votre compte sur Gite de la Régordane',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('app_home');
    }
}
