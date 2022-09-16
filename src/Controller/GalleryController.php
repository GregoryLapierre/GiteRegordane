<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(ImageRepository $imagerepository): Response
    {
        $kitchen = $imagerepository->findByCategory('Cuisine/salon');
        $bedroomOne = $imagerepository->findByCategory('Chambre 1');
        $bedroomTwo = $imagerepository->findByCategory('Chambre 2');

        return $this->render('gallery/index.html.twig', [
            'kitchens' => $kitchen,
            'bedroomsOne' => $bedroomOne,
            'bedroomsTwo' => $bedroomTwo
        ]);
    }
}
