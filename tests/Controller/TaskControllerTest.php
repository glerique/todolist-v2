<?php

namespace App\Tests\Controller;


use App\Tests\Controller\SecurityControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    public function testListActionIfNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testListActionAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $client->request('GET', '/tasks');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testListActionIsDoneAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $client->request('GET', '/done');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testListActionToDoAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $client->request('GET', '/todo');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        $this->assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Test';
        $form['task[content]'] = 'Test';
        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditActionAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $crawler = $client->request('GET', '/tasks/8/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        $this->assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Test modification';
        $form['task[content]'] = 'Test modification';
        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditActionWithNoUserAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();

        $crawler = $client->request('GET', '/tasks/4/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        $this->assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Test modification';
        $form['task[content]'] = 'Test modification';
        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Create as admin';
        $form['task[content]'] = 'Create as admin';
        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditActionAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $crawler = $client->request('GET', '/tasks/9/edit');


        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = "Edit as admin";
        $form['task[content]'] = "Edit as admin";
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testToogleTaskActionAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $client->request('GET', '/tasks/10/toggle');

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testDeleteActionIfNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskActionOfUserAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $client->request('GET', '/tasks/12/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskActionOfAnonymousAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $client->request('GET', '/tasks/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testDeleteTaskActionOfAnonymousAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();
        $client->request('GET', '/tasks/2/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
    
    public function testDeleteTaskActionOfUserAsSelfUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAnonyme();
        $client->request('GET', '/tasks/3/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());        
    }    
    

    public function testDeleteTaskActionIfNotSelfUserAsUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsUser();
        $client->request('GET', '/tasks/13/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
}
