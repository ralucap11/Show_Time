<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/artist/add_new_artist', name: 'app_artist_add_new', methods: ['GET', 'POST'])]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {

        $task = new Artist();
        $form = $this->createFormBuilder($task)
            ->add('nume', TextType::class)
            ->add('gen_muzical',TextType ::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_festival_index');

        }
        return $this->render('artist/add.html.twig', [
            'form' => $form,

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
