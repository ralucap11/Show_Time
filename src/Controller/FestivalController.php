<?php

namespace App\Controller;
use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    #[Route('/festival/add_new_festival', name: 'app_festival_add_new', methods: ['GET', 'POST'])]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {

          $task = new Festival();
          $form = $this->createFormBuilder($task)
            ->add('nume', TextType::class)
            ->add('locatie',TextType ::class)
              ->add('start_date',DateType ::class)
              ->add('end_date',DateType ::class)
              ->add('price',NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Add festival'])
            ->getForm();

             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                 $task = $form->getData();
                $entityManager->persist($task);
                $entityManager->flush();
                 return $this->redirectToRoute('app_festival_index');

             }
           return $this->render('festival/add.html.twig', [
               'form' => $form,
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

    #[Route('/festival', name: 'app_festival_index')]
    public function list(Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        $queryBuilder = $entityManager->getRepository(Festival::class)->createQueryBuilder('e');

             $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('festival/index.html.twig', [
            'pagination' => $pagination,
            'festivals' => $pagination,
        ]);

    }

    #[Route('/festival/{id}/edit', name: 'app_festival_edit')]
    public function edit(Festival $festival, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder($festival)
            ->add('nume', TextType::class)
            ->add('locatie',TextType ::class)
            ->add('start_date',DateType ::class)
            ->add('end_date',DateType ::class)
            ->add('price',NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'modify festival'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'festival modified');
            return $this->redirectToRoute('app_festival_index');
        }

        return $this->render('festival/edit.html.twig', [
            'form' => $form->createView(),
            'festival' => $festival,
        ]);
    }




}
