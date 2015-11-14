<?php

namespace Debril\FeedIoBundle\Tests\Controller;

class StreamControllerTest extends WebDbTestCase
{

    public function setUp()
    {
        parent::setUp();
        self::runCommand('doctrine:fixtures:load -n');
    }

    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/rss/1');

        $response = $client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());
        $lastModified = $response->getLastModified();

        $lastModified->setTimezone(
                new \DateTimeZone(date_default_timezone_get())
        );

        $lastModified->add(new \DateInterval('PT1S'));
        $this->assertInstanceOf('\DateTime', $lastModified);
        $this->assertGreaterThan(0, $response->getMaxAge());
        $this->assertGreaterThan(0, strlen($response->getContent()));
        $this->assertTrue($response->isCacheable());

        $client->request('GET', '/rss/1', array(), array(), array('HTTP_If-Modified-Since' => $lastModified->format(\DateTime::RSS)));
        $response2 = $client->getResponse();

        $this->assertEquals('304', $response2->getStatusCode());
        $this->assertEquals(0, strlen($response2->getContent()));
    }

    /**
     * 
     */
    public function testNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/rss/not-found');
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}
