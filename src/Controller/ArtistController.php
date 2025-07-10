<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Entity\Festival;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ArtystType;



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
    #[Route('/artist/add_new_artist', name: 'app_artist_add_new')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtystType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('artist_images_directory'),
                        $newFilename
                    );
                    $artist->setImageFile($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was a problem uploading your image');
                }
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_index');
        }

        return $this->render('artist/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/artist', name: 'app_artist_index')]
    public function list(Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        $queryBuilder = $entityManager->getRepository(Artist::class)->createQueryBuilder('e');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('artist/index.html.twig', [
            'pagination' => $pagination,
            'artists' => $pagination,
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
