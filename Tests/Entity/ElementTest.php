<?php

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Element;
use Debril\FeedIoBundle\Tests\KernelDbTestCase;

class ElementTest extends KernelDbTestCase
{

    public function testAttributes()
    {
        $element = new Element();
        $element->setAttribute('foo', 'bar');
        $element->setName('test');
        
        $this->em->persist($element);
        $this->em->flush();
        
        $id = $element->getId();
        
        $element = current($this->em->getRepository('DebrilFeedIoBundle:Element')->findById($id));
        
        $this->assertEquals('bar', $element->getAttribute('foo'));
        
    }
    
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testMissingAttribute()
    {
        $element = new Element;
        $element->getAttribute('waldo');
    }
    
}
