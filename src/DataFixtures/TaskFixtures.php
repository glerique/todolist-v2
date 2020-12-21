<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TaskFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        
        for ($i = 1; $i < 11; $i++) {
            $task = new Task();
            $task->setCreatedAt(new \DateTime('now'));            
            $task->setTitle("title$i");
            $task->setContent("Content$i");
            $task->toggle(rand(0,1));                

            $manager->persist($task);
           
        }

        $manager->flush();
    }
      
}
