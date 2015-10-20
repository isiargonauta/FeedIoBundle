<?php

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Element;
use Debril\FeedIoBundle\Entity\ElementSet;
use Debril\FeedIoBundle\Tests\KernelDbTestCase;

class ElementSetTest extends KernelDbTestCase
{

    public function testElement()
    {
        $element = new Element();
        $element->setName('test');
        
        $elementSet = new ElementSet();
        $elementSet->addElement($element);
        $this->em->persist($elementSet);
        $this->em->flush();
        
        $id = $elementSet->getId();
        
        $elementSet = current($this->em->getRepository('DebrilFeedIoBundle:ElementSet')->findById($id));
        
        $this->assertInstanceOf('\DateTime', $elementSet->getCreatedAt());
        $count = 0;
        foreach($elementSet->getElements() as $element) {
            $count++;
            $this->assertEquals('test', $element->getName());
        }
        
        $this->assertEquals(1, $count);
        
        
        $element = new Element();
        $element->setName('test 2');
        $elementSet->addElement($element);
    }
    
}
