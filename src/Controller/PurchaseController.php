<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'app_purchase_index')]
    public function index(PurchaseRepository $purchaseRepository): Response
    {
        $purchases= $purchaseRepository->findAll();

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases,
        ]);
    }

    #[Route('/purchase/{id}', name: 'app_purchase_show')]
    public function show(int $id, PurchaseRepository $purchaseRepository): Response
    {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase) {
            throw $this->createNotFoundException('Purchase not found');
        }

        return $this->render('purchase/show.html.twig', [
            'purchase' => $purchase,
        ]);
    }
}
