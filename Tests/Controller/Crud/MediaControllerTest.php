<?php

namespace Debril\FeedIoBundle\Tests\Controller\Crud;

use Debril\FeedIoBundle\Tests\Controller\WebDbTestCase;

class MediaControllerTest extends WebDbTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/crud/media/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /crud/media/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'debril_feediobundle_media[type]'  => 'audio/mp3',
            'debril_feediobundle_media[url]'  => 'http://',
            'debril_feediobundle_media[length]'  => '1234',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("audio/mp3")')->count(), 'Missing element td:contains("audio/mp3")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'debril_feediobundle_media[type]'  => 'video/mpeg',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="video/mpeg"]')->count(), 'Missing element [value="video/mpeg"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/video/', $client->getResponse()->getContent());
    }

}
