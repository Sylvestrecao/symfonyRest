<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTokenControllerTest extends WebTestCase
{
    public function testPostauthtoken()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth-tokens');
    }

    public function testInvalidcredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/invalidCredentials');
    }

}
