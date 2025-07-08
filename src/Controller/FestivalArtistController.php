<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\FestivalArtist;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
#[Route('festival/artist/{id}/add', name: 'app_festival_artist_add')]
public function add(Festival $festival, EntityManagerInterface $entityManager, Request $request): Response
{
     $festivalArtist = new FestivalArtist();
     $festivalArtist->setFestival($festival);
     $form = $this->createFormBuilder($festivalArtist)
         ->add('festival', EntityType::class, [
             'class' => Festival::class,
             'choice_label' => 'nume',
             'placeholder' => 'Choose Festival',
         ])
         ->add('artist', EntityType::class, [
             'class' => Artist::class,
             'choice_label' => 'nume',
             'placeholder' => 'Choose Artist',
         ])
         ->getForm();
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
         $entityManager->persist($festivalArtist);
         $entityManager->flush();
         return $this->redirectToRoute('app_festival_show', [
             'id' => $festival->getId(),
         ]);
     }
     return $this->render('festival_artist/new.html.twig', [
         'form' => $form->createView(),
     ]);
}


}
