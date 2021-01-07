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
        $anonyme->setRoles(['ROLE_USER']);
        
        $manager->persist($anonyme);
        
        for ($u = 1; $u < 11; $u++) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setUsername("User$u");
            $user->setPassword("$password");
            $user->setEmail("user$u@todolist.fr");
            $user->setRoles(['ROLE_USER']);                            

            $manager->persist($user);
            $users[] = $user;
        }
        
        
        for ($i = 1; $i < 11; $i++) {
            $user = $users[mt_rand(0, count($users) - 1)];

            $task = new Task();
            $task->setCreatedAt(new \DateTime('now'));            
            $task->setTitle("title$i");
            $task->setContent("Content$i");
            $task->toggle(rand(0,1));                
            $task->setUser($user);
            $manager->persist($task);
           
        }

        $manager->flush();
    }
}
