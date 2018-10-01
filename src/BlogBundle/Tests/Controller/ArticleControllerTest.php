<?php

namespace BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testAdduser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addUser');
    }

}
