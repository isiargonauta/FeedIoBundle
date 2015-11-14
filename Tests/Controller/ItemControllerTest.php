<?php

namespace Debril\FeedIoBundle\Tests\Controller;

class ItemControllerTest extends WebDbTestCase
{

    public function setUp()
    {
        parent::setUp();
        self::runCommand('doctrine:fixtures:load -n');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/item/list/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /item/list/1");
        $content = $client->getResponse()->getContent();
        $this->assertGreaterThan(0, strpos($content, '<h1>PHP : Hypertext Preprocessor</h1>'), 'Missing feed title');
        $this->assertGreaterThan(0, strpos($content, '<h2>test</h2>'), 'Missing item title');

    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/item/new/1');

    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete');
    }

}
