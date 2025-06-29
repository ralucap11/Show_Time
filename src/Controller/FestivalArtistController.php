<?php

namespace App\Controller;


use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class FestivalArtistController extends AbstractController
{
    #[Route('/festival/artist', name: 'app_festival_artist')]
    public function index(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        return $this->render('festival_artist/index.html.twig', [
            'festivals' => $festivals,
        ]);
    }
}
