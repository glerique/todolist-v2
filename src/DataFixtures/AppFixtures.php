<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        
        $admin = new User();
        $password = $this->encoder->encodePassword($admin, 'password');        
        $admin->setUsername('admin');
        $admin->setPassword($password);
        $admin->setEmail('admin@totodolist.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);        


        $anonyme = new User();
        $password = $this->encoder->encodePassword($anonyme, 'password');        
        $anonyme->setUsername('anonyme');
        $anonyme->setPassword($password);
        $anonyme->setEmail('anonyme@totodolist.fr');        
        
        $manager->persist($anonyme);

        
        for ($u = 1; $u < 11; $u++) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setUsername("User$u");
            $user->setPassword("$password");
            $user->setEmail("user$u@todolist.fr");
            $manager->persist($user);
            $users[] = $user;
        }

        
        for ($a = 1; $a < 5; $a++) {            
             
            $task = new Task();
            $task->setCreatedAt(new \DateTime('now'));            
            $task->setTitle("Title anonyme$a");
            $task->setContent("Content anonyme$a");
            $task->toggle(rand(0,1));
            if ($a == 1 or $a == 2 or $a == 3 ){ $task->setUser($anonyme); } 
            $manager->persist($task);
           
        }

        for ($i = 1; $i < 11; $i++) {
            $user = $users[mt_rand(4, count($users) - 1)];

            $task = new Task();
            $task->setCreatedAt(new \DateTime('now'));            
            $task->setTitle("Title user$i");
            $task->setContent("Content user$i");
            $task->toggle(rand(0,1)); 
            $task->setUser($user);
            $manager->persist($task);
           
        }

        $manager->flush();
    }
}
