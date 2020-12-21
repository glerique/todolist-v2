<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        
        for ($i = 1; $i < 11; $i++) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setUsername("User$i");
            $user->setPassword("$password");
            $user->setEmail("user$i@todolist.fr");                

            $manager->persist($user);
           
        }

        $manager->flush();
    }
}
