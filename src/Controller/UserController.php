<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{



    #[Route('/user', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
        'user' => $user,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {     // dd($id);
        $user =  $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');

    }


    #[Route('/user', name: 'app_user_index')]
    public function list(Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        $queryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('e');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'users' => $pagination,
        ]);
    }







}
