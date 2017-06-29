<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile/facebook/4');

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals('{"id":4,"firstName":"Mark","lastName":"Zuckerberg"}', $client->getResponse()->getContent());

    }
}
