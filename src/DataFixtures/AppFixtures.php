<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<20; $i++)
        {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'password');
            $domains = ['@gmail.com', '@email.com'];
            $email = 'user' . $i . $domains[array_rand($domains)];
            $user->setEmail($email);
            $user->setPassword($password);
            $manager->persist($user);
        }
        $manager->flush();
    }
}




//public function load(ObjectManager $manager): void
//{   $characters = 'abcdefghijklmnopqrstuwxyz';
//    $name = '';
//    $cities = ['Timisoara', 'Cluj', 'Brasov','Oradea', 'Viena','Paris','Budapesta','Roma'];
//    for($i = 1; $i <= 15; $i++) {
//        $festival = new Festival();
//        for($j = 1; $j < 10; $j++)
//        {
//            $name .= $characters[rand(0, strlen($characters) - 1)];
//        }
//        $festival->setNume($name);
//        $festival->setLocatie($cities[array_rand($cities)]);
//        $festival->setStartDate(new \DateTime());
//        $festival->setEndDate(new \DateTime());
//        $festival->setPrice(rand(50,700));
//        $manager->persist($festival);
//    }
//    $manager->flush();
//}
