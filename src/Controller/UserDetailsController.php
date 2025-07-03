<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserDetails;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserDetailsController extends AbstractController
{
    #[Route('/user/details', name: 'app_user_details')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user_details/index.html.twig', [
            'users' => $users,


        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);


        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/details', name: 'app_user_details_show')]
    public function showDetails(User  $user): Response
    {

        return $this->render('user_details/show.html.twig', [
            'user' => $user,

    ]);
    }




}
