<?php

namespace App\Controller;


use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist_index')]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();

        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }
    #[Route('/artist/{id}', name: 'app_artist_show')]
public function show(int $id,ArtistRepository  $artistRepository): Response
    {
        $artist = $artistRepository->find($id);
        if(!$artist)
        {
            throw $this->createNotFoundException('Artist not found');
        }

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }
}
