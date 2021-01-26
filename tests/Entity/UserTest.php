<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertEquals(null, $user->getId());
    }

    public function testGetSetUsername()
    {
        $user = new User();
        $user->setUsername('Test username');
        $this->assertEquals('Test username', $user->getUsername());
    }

    public function testGetSetPassword()
    {
        $user = new User();
        $user->setPassword('Test password');
        $this->assertEquals('Test password', $user->getPassword());
    }

    public function testGetSetEmail()
    {
        $user = new User();
        $user->setEmail('Test@email.com');
        $this->assertEquals('Test@email.com', $user->getEmail());
    }

    public function testGetSalt()
    {
        $user = new User();
        $this->assertEquals(null, $user->getSalt());
    }

    public function testGetAddTask()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user->AddTask(new Task()));
        $this->assertInstanceOf(ArrayCollection::class, $user->getTasks());
        $this->assertContainsOnlyInstancesOf(Task::class, $user->getTasks());
    }

    public function testRemoveTask()
    {
        $user = new User();
        
        $this->assertInstanceOf(User::class, $user->removeTask(new Task()));
        $this->assertEmpty($user->getTasks());

        
        $task = new Task();
        $user->addTask($task);
        $user->removeTask($task);
        $this->assertEmpty($user->getTasks());
        $this->assertInstanceOf(User::class, $user->removeTask(new Task()));
    }

    public function testGetSetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        $this->assertEquals(null, $user->eraseCredentials());
    }  
    
}