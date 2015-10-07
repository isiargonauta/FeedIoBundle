<?php

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
                                   ->get('doctrine')
                                   ->getManager();
    }

    public function testDatesChange()
    {
        $item = new Item();
        $item->setPublishedAt(new \DateTime());
        $item->setLastModified(new \DateTime());
        $item->setTitle('foo');
        $item->setDescription('lorem ipsum');
        $item->setPublicId('aaa');
        $item->setLink('http');
        
        $this->assertInstanceOf('\DateTime', $item->getCreatedAt());
        
        $this->em->persist($item);
        $this->em->flush();
        
        $id = $item->getId();
        $createdAt = $item->getCreatedAt();
        $modifiedAt = $item->getModifiedAt();
        sleep(3);
        $item = current($this->em->getRepository('DebrilFeedIoBundle:Item')->findById($id));
        $item->setTitle('bar');
        $this->em->persist($item);
        $this->em->flush();
        unset($item);
        
        $freshItem = current($this->em->getRepository('DebrilFeedIoBundle:Item')->findById($id));
        
        $this->assertEquals($createdAt, $freshItem->getCreatedAt());
        $this->assertNotEquals($modifiedAt, $freshItem->getModifiedAt());
    }

}
