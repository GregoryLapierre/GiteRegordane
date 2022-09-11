<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FindUsController extends AbstractController
{
    #[Route('/findus', name: 'app_find_us')]
    public function index(): Response
    {
        return $this->render('find_us/index.html.twig', [
            'controller_name' => 'FindUsController',
        ]);
    }
}
