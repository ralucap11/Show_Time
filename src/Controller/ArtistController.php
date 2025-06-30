<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/artist/delete/{id}', name: 'app_artist_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $artist =  $entityManager->getRepository(Artist::class)->find($id);
        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->redirectToRoute('app_artist_index');

    }

}
