<?php

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Tests\KernelDbTestCase;

class FeedTest extends KernelDbTestCase
{

    public function testDatesChange()
    {
        $feed = new Feed();

        $feed->setLastModified(new \DateTime());
        $feed->setTitle('foo');
        $feed->setDescription('lorem ipsum');
        $feed->setPublicId('aaa');
        $feed->setLink('http');
        $feed->setType(2);
        
        $this->assertInstanceOf('\DateTime', $feed->getCreatedAt());
        
        $this->em->persist($feed);
        $this->em->flush();
        
        $id = $feed->getId();
        $createdAt = $feed->getCreatedAt();
        $modifiedAt = $feed->getModifiedAt();
        sleep(3);
        $item = current($this->em->getRepository('DebrilFeedIoBundle:Feed')->findById($id));
        $feed->setTitle('bar');
        $this->em->persist($feed);
        $this->em->flush();
        unset($feed);
        
        $freshFeed = current($this->em->getRepository('DebrilFeedIoBundle:Feed')->findById($id));
        
        $this->assertEquals($createdAt, $freshFeed->getCreatedAt());
        $this->assertNotEquals($modifiedAt, $freshFeed->getModifiedAt());
    }
    
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testUnexpectedType()
    {
        $feed = new Feed;
        $feed->setType(4);
    }
    
}
