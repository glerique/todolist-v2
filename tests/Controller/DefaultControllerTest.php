<?php

namespace Tests\App\Controller;

use App\Tests\Controller\SecurityControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testHomepageIsUpIfLoggedAsAdmin()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    } 
}