<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{slug}');
    }

    public function testRent()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{slug}/rent');
    }

}
