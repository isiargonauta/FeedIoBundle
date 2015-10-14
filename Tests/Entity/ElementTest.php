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
    
    public function testName()
    {
        $element = new Element();
        $element->setName('test');
        
        $this->assertEquals('test', $element->getName());
    }
     
    public function testValue()
    {
        $element = new Element();
        $element->setName('test');
        $element->setValue('foo bar');
        $this->assertEquals('foo bar', $element->getValue());
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
