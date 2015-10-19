<?php

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Item;
use Debril\FeedIoBundle\Entity\Media;
use Debril\FeedIoBundle\Tests\KernelDbTestCase;

class ItemTest extends KernelDbTestCase
{

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
    
    public function testMedias()
    {
        $item = new Item();
        $media = $item->newMedia();
        
        $media->setUrl('http');
        
        $item->addMedia($media);
        
        $this->assertTrue($item->hasMedia());
        
        $count = 0;
        $this->assertInstanceOf('\Iterator', $item->getMedias(), 'getMedias must return an Iterator instance');
        
        foreach( $item->getMedias() as $element ) {
            $count++;
            $this->assertEquals('http', $media->getUrl(), 'media url must be foo');
        }
        
        $this->assertEquals(1, $count, 'the item holds one media');
    }
    
}
