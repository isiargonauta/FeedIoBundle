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
        $this->assertGreaterThan(0, strpos($content, '<h2>great test item</h2>'), 'Missing item title');

    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/item/new/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /item/new/1");

        $form = $crawler->selectButton('Save')->form(array(
            'debril_feediobundle_feed_item[publishedAt][date][day]'  => 1,
            'debril_feediobundle_feed_item[publishedAt][date][month]'  => 1,
            'debril_feediobundle_feed_item[publishedAt][date][year]'  => 2015,
            'debril_feediobundle_feed_item[publishedAt][time][hour]'  => 12,
            'debril_feediobundle_feed_item[publishedAt][time][minute]'  => 0,
            'debril_feediobundle_feed_item[title]'  => 'item test',
            'debril_feediobundle_feed_item[publicId]'  => 'item id',
            'debril_feediobundle_feed_item[description]'  => 'lorem ipsum',
        ));
        $client->submit($form);

        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'item test'), 'missing item title');

    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/item/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /item/1/edit");

        $form = $crawler->selectButton('Save')->form(array(
            'debril_feediobundle_feed_item[publishedAt][date][day]'  => 1,
            'debril_feediobundle_feed_item[publishedAt][date][month]'  => 1,
            'debril_feediobundle_feed_item[publishedAt][date][year]'  => 2015,
            'debril_feediobundle_feed_item[publishedAt][time][hour]'  => 12,
            'debril_feediobundle_feed_item[publishedAt][time][minute]'  => 0,
            'debril_feediobundle_feed_item[title]'  => 'great title',
            'debril_feediobundle_feed_item[publicId]'  => 'item id',
            'debril_feediobundle_feed_item[description]'  => 'lorem ipsum',
        ));
        $client->submit($form);
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'great title'), 'item title not updated');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/item/1/delete');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /item/1/delete");        
        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, strpos($content, 'great test item'), 'missing item title');
        $client->submit($crawler->selectButton('Delete')->form());        
        
        $crawler = $client->followRedirect();
        $content = $client->getResponse()->getContent();

        $this->assertEquals(0, strpos($content, '<h2>great test item</h2>'), 'item not removed');
    }

}
