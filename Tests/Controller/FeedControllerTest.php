<?php

namespace Debril\FeedIoBundle\Tests\Controller;

class FeedControllerTest extends WebDbTestCase
{
    public function testAdd()
    {
        $client = static::createClient();
#var_dump($client->getContainer());
        $crawler = $client->request('GET', '/feed/add');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /feed/add");
        
        $form = $crawler->selectButton('Add')->form(array(
            'debril_feediobundle_feed_external[link]'  => __DIR__ . '/../../Resources/samples/sample-atom.xml',
            // ... other fields to fill
        ));
        
        $client->submit($form);

        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'Example Feed'), 'Missing feed title');
        $this->assertGreaterThan(0, strpos($content, 'Atom-Powered'), 'Missing item title');
        
    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/new');
    }

    public function testSave()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/save');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/show');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/edit');
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/update');
    }

}
