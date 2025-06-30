<?php

namespace App\Controller;


use App\Entity\FestivalArtist;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
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
//
//    #[Route('/festival/artist/delete/{id}', name: 'app_festival_artist_delete', methods: ['GET'])]
//    public function delete(EntityManagerInterface $entityManager, int $id): Response
//    {     // dd($id);
//        $festivalArtist =  $entityManager->getRepository(FestivalArtist::class)->find($id);
//        $entityManager->remove($festivalArtist);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('app_festival_index');
//
//    }
}
