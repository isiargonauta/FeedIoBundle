<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/07/15
 * Time: 18:37
 */

namespace Debril\FeedIoBundle\Tests\Entity;

use Debril\FeedIoBundle\Entity\Node;
use Debril\FeedIoBundle\Entity\Element;
use Debril\FeedIoBundle\Entity\ElementSet;
#use FeedIo\Feed\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{

    public function testSetElement()
    {        
        $node = $this->getNode();
        $this->assertInstanceOf('\Debril\FeedIoBundle\Entity\ElementSet', $node->getElementSet(), 'getElementSet must return an ElementSet instance');
        
        $count = 0;
        foreach( $node->listElements() as $name ) {
            $count++;
            $this->assertEquals('foo', $name, 'element name must be foo');
        }

        $this->assertEquals(1, $count, 'the node holds only one element');
    }

    public function testGetElementIterator()
    {
        $node = $this->getNode();
        
        $count = 0;
        foreach( $node->getElementIterator('foo') as $element ) {
            $count++;
            $this->assertEquals('foo', $element->getName(), 'element name must be foo');
            $this->assertEquals('bar', $element->getValue(), 'element name must be foo');
        }
        
        $this->assertEquals(1, $count, 'the node holds only one element');
    }
    
    public function testGetAllElements()
    {
        $node = $this->getNode();
        
        $count = 0;
        $this->assertInstanceOf('\Iterator', $node->getAllElements(), 'getAllElements must return an Iterator instance');
        
        foreach( $node->getAllElements() as $element ) {
            $count++;
            $this->assertEquals('foo', $element->getName(), 'element name must be foo');
            $this->assertEquals('bar', $element->getValue(), 'element name must be foo');
        }
        
        $this->assertEquals(1, $count, 'the node holds only one element');
    }
    protected function getNode()
    {
        $node = new Node();        
        
        $element = $node->newElement();
        $element->setName('foo');
        $element->setValue('bar');

        $elementSet = new ElementSet();
        $elementSet->addElement($element);
        $node->setElementSet($elementSet);
        
        return $node;

    }

}
