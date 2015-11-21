<?php

namespace Debril\FeedIoBundle\Tests\Controller;

class FeedControllerTest extends WebDbTestCase
{
    public function testAdd()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/feed/add');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /feed/add");
        
        $form = $crawler->selectButton('Add')->form(array(
            'debril_feediobundle_feed_external[url]'  => __DIR__ . '/../../Resources/samples/sample-atom.xml',
        ));
        
        $client->submit($form);

        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'Example Feed'), 'Missing feed title');
        $this->assertGreaterThan(0, strpos($content, 'Atom-Powered'), 'Missing item title');
        
    }

    public function testNewAndEdit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/feed/new');     
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /feed/new");
        
        $form = $crawler->selectButton('Save')->form(array(
            'debril_feediobundle_feed_published[type]'  => 2,
            'debril_feediobundle_feed_published[title]'  => 'Test Feed',
            'debril_feediobundle_feed_published[publicId]'  => '1',
            'debril_feediobundle_feed_published[comment]'  => 'a comment',
            'debril_feediobundle_feed_published[description]'  => 'something',
        ));
        
        $client->submit($form);

        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();
        
        $this->assertGreaterThan(0, strpos($content, 'Test Feed'), 'Missing feed title');
        $this->assertGreaterThan(0, strpos($content, 'a comment'), 'Missing comment');
        
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Save')->form(array(
            'debril_feediobundle_feed_published[title]'  => 'New Title',            
            'debril_feediobundle_feed_published[publicId]'  => '1',
            'debril_feediobundle_feed_published[comment]'  => 'a comment',
            'debril_feediobundle_feed_published[description]'  => 'something',
        ));
         
        $client->submit($form);
        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();
        
        $this->assertGreaterThan(0, strpos($content, 'New Title'), 'Wrong feed title');
    }

    public function testShow()
    {
        self::runCommand('doctrine:fixtures:load -n');
        $client = static::createClient();

        $crawler = $client->request('GET', '/feed/1/show');   
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /feed/1/show");     
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'FeedIoBundle'), 'Missing feed title');
        
        $crawler = $client->click($crawler->selectLink('RSS')->link());
        $content = $client->getResponse()->getContent();

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /rss/1");
        
        $this->assertGreaterThan(0, strpos($content, 'FeedIoBundle'), 'Missing feed title');
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
