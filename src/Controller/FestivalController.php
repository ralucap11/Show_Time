<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    #[Route('/festival', name: 'app_festival_index')]
    public function index(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivals,
        ]);
    }

    #[Route('/festival/{id}', name: 'app_festival_show')]
    public function show(Festival $festival, Artist $artists): Response
    {

        return $this->render('festival/show.html.twig', [
            'festival' => $festival,
             'artists' => $artists,  //?
        ]);
    }
}
