<?php

namespace Debril\FeedIoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Debril\FeedIoBundle\Tests\CommandTrait;

class WebDbTestCase extends WebTestCase
{

    use CommandTrait;

    public function setUp()
    {
        $this->createDb();
    }
    
    public function tearDown()
    {
        $this->dropDb();
    }
    
} 