<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Purchase;
use App\Entity\User;
use App\Repository\FestivalRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'app_purchase_index', methods: ['GET'])]
    public function index(PurchaseRepository $purchaseRepository): Response
    {
        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchaseRepository->findAll()
        ]);
    }



    #[Route('/purchase/user/{id}', name: 'app_user_purchase_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $purchase = $user->getPurchases();

        return $this->render('purchase/show.html.twig', [
            'user' => $user,
            'purchase' => $purchase,
        ]);
    }


    #[Route('/purchase/delete/{id}', name: 'app_purchase_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $purchase =  $entityManager->getRepository(Purchase::class)->find($id);
        $entityManager->remove($purchase);
        $entityManager->flush();

        return $this->redirectToRoute('app_purchase_index');

    }

    #[Route('/purchase/create/{festival_id}', name: 'app_purchase_create', methods: ['GET'])]
    public function create(int $festival_id, EntityManagerInterface $em, FestivalRepository $festivalRepo, Request $request): Response {
        $festival = $festivalRepo->find($festival_id);
        $quantity = $request->query->getInt('quantity', 1);
        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        if ($quantity >$festival->getNumberTickets()) {
            $this->addFlash('error', 'No tickets available for this festival!');
            return $this->redirectToRoute('app_festival_index');
        }


        $purchase = new Purchase();
        $purchase->setFestival($festival);
        $purchase->setUser($this->getUser());
        $purchase->setPurchaseDate(new \DateTime());
        $purchase->setTickets($quantity);

        $festival->setNumberTickets($festival->getNumberTickets() - $quantity);  //festival ticket count

        $em->persist($purchase);
        $em->flush();

        $this->addFlash('success', 'Ticket purchased successfully!');
        return $this->redirectToRoute('app_festival_index');
    }



    #[Route('/purchase/confirm/{festival_id}', name: 'app_purchase_confirm', methods: ['GET'])]
    public function confirm(EntityManagerInterface $entityManager, int $festival_id): Response
    {
        $festival = $entityManager->getRepository(Festival::class)->find($festival_id);
        $user=$this->getUser();
        if(!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        if(!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('purchase/confirm.html.twig', [
            'festival' => $festival,
             'user' => $user

        ]);
    }

}
