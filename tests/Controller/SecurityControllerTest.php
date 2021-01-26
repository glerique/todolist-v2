<?php

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    
    public function testLoginAsAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'password';
        $client->submit($form);

        $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        return $client;
    }

    public function testLoginAsUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        
        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user1';
        $form['_password'] = 'password';
        $client->submit($form);

        $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        return $client;
    }

    public function testLoginAsAnonyme()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        
        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'anonyme';
        $form['_password'] = 'password';
        $client->submit($form);

        $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        return $client;
    }
    

    public function testLoginWithWrongCredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'azerty';
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("Invalid credentials.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testLoginCheck()
    {
        $securityController = new SecurityController();

        $check = $securityController->loginCheck();
        $this->assertNull($check);
    }

    public function testLogout()
    {
        $securityController = new SecurityController();

        $check = $securityController->logoutCheck();
        $this->assertNull($check);
    }
}