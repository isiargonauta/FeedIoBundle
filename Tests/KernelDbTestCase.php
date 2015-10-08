<?php

namespace Debril\FeedIoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Debril\FeedIoBundle\Tests\CommandTrait;

class KernelDbTestCase extends KernelTestCase
{

    use CommandTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
                                   ->get('doctrine')
                                   ->getManager();
        $this->createDb();                        
    }

    public function tearDown()
    {
        $this->dropDb();
    }
    
}
