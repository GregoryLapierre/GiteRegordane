<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(ImageRepository $imageRepository): Response
    {
        $kitchen = $imageRepository->findByCategory('Cuisine/salon');
        $bedroomOne = $imageRepository->findByCategory('Chambre 1');
        $bedroomTwo = $imageRepository->findByCategory('Chambre 2');

        return $this->render('gallery/index.html.twig', [
            'kitchens' => $kitchen,
            'bedroomsOne' => $bedroomOne,
            'bedroomsTwo' => $bedroomTwo
        ]);
    }
}
