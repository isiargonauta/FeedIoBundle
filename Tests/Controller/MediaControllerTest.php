<?php

namespace Debril\FeedIoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;

class MediaControllerTest extends WebTestCase
{

    protected static $application;


    public function setUp()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new \Symfony\Bundle\FrameworkBundle\Console\Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/media/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /media/");
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
