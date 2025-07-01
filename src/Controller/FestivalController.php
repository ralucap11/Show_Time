<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/festival/add', name: 'app_festival_add', methods: ['GET', 'POST'])]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {
        $nume = $request->request->get('nume');
        $locatie = $request->request->get('locatie');
        $start_date = $request->request->get('start_date');
        $end_date = $request->request->get('end_date');
        $price = $request->request->get('price');

        $festival = new Festival();
        $festival->setNume($nume);
        $festival->setLocatie($locatie);
        $festival->setStartDate(new DateTime($start_date));
        $festival->setEndDate(new DateTime($end_date));
        $festival->setPrice($price);


        $entityManager->persist($festival);
        $entityManager->flush();

        return $this->redirectToRoute('app_festival_index');
    }

    #[Route('/festival/add_new_festival', name: 'app_festival_add_new', methods: ['GET', 'POST'])]
    public function newFestival(): Response
    {

        return $this->render('festival/add.html.twig', [

        ]);
    }

    #[Route('/festival/{id}', name: 'app_festival_show')]
    public function show(Festival $festival): Response
    {
        $festivalArtists = $festival->getFestivalArtists();

        return $this->render('festival/show.html.twig', [
            'festival' => $festival,
            'festivalArtists' => $festivalArtists,
        ]);
    }



    #[Route('/festival/delete/{id}', name: 'app_festival_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {     // dd($id);
        $festival =  $entityManager->getRepository(Festival::class)->find($id);
          $entityManager->remove($festival);
     $entityManager->flush();

        return $this->redirectToRoute('app_festival_index');

    }


}
