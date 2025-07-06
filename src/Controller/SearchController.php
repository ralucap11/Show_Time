<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        $form = $this->createFormBuilder(null, [
            'action' => $this->generateUrl('app_search'),
            'method' => 'GET',
        ])
            ->add('query', SearchType::class, [
                'attr' => ['placeholder' => 'Search']
            ])
            ->getForm();
        return $this->render('search/index.html.twig', [
        'form' => $form->createView(),
            ]);
    }


    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $results = [];
        $query=null;
        if ($form->isSubmitted() && $form->isValid()) {
            $query=$form->get('query')->getData();
            if(!empty($query))
            {
                $results['festival'] = $entityManager->getRepository('App\Entity\Festival')
                    ->createQueryBuilder('f')
                    ->where('p.name LIKE :query')
                    ->setParameter('query', '%'.$query.'%')
                    ->getQuery()
                    ->getResult();
                $results['artist'] = $entityManager->getRepository('App\Entity\Artist')
                    ->createQueryBuilder('a')
                    ->where('a.name LIKE :query')
                    ->setParameter('query', '%'.$query.'%')
                    ->getQuery()
                    ->getResult();

                $results['user'] = $entityManager->getRepository('App\Entity\User')
                    ->createQueryBuilder('u')
                    ->where('u.name LIKE :query')
                    ->setParameter('query', '%'.$query.'%')
                    ->getQuery()
                    ->getResult();
            }

        }
        return $this->render('search/search.html.twig', [
            'results' => $results,
            'query' => $query,
            'form' => $form->createView(),
        ]);

    }
}
