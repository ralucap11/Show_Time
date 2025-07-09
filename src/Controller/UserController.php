<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserDetails;
use App\Repository\UserDetailsRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[Route('/account', name: 'app_user_account')]
    #[IsGranted('ROLE_USER')]
    public function account(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('user/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/add_new', name: 'app_user_add_new', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $task = new User();
        $userDetails = new UserDetails();
        $task->setUserDetails($userDetails);

        $form = $this->createFormBuilder($task)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('userDetails', FormType::class, [
                'label' => false,
                'data_class' => UserDetails::class,
                'by_reference' => false,
            ])
            ->getForm();

        $form->get('userDetails')
            ->add('name', TextType::class)
            ->add('varsta', IntegerType::class, [
                'attr' => ['min' => 16,
                 'value' => 16   //default so it appears 16 not 0 in the form
                ]
            ])
            ->add('rol', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                ],
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $task,
                $form->get('password')->getData()
            );
            $task->setPassword($hashedPassword);

            try {
                //for the error UNIQUE_VALUE
                // $existing = $userDetailsRepo->findOneBy(['username' => $task->getUserDetails()->getUsername()]);
                // if ($existing) {
                //     $this->addFlash('error', 'this username already exists');
                //     return $this->redirectToRoute('app_user_add_new');
                // }

                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('app_user_index');

            } catch (UniqueConstraintViolationException $e) {    //error unique fixed
                $this->addFlash('error', 'This value already exists in our system');
                return $this->redirectToRoute('app_user_add_new');
            }
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'user' => $task,
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
