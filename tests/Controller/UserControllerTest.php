<?php

namespace App\Tests\Controller;

use App\Tests\Controller\SecurityControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListActionWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    public function testListActionAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();

        $client->request('GET', '/users');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
    
    public function testListActionAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $client->request('GET', '/users');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    } 
    
    public function testCreateAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $crawler = $client->request('GET', '/users/create');
        $random = mt_rand(1, 10000);

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $random . 'testi';
        $form['user[password][first]'] = 'testi';
        $form['user[password][second]'] = 'testi';
        $form['user[email]'] = mt_rand(1, 10000) . 'testi@testi.fr';
        $form['user[roles]'];
        $crawler = $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }    

    public function testEditActionAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $crawler = $client->request('GET', '/users/6/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = "username";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "password";
        $form['user[email]'] = "username@todolist.fr";
        $form['user[roles]'] = ["ROLE_USER", "ROLE_ADMIN"];        
        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());        
    }
}
    
    
