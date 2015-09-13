<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/07/15
 * Time: 18:37
 */

namespace Debril\FeedIoBundle\Tests\Entity;


use Debril\FeedIoBundle\Entity\Element;
use Debril\FeedIoBundle\Entity\ElementSet;
use FeedIo\Feed\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{

    public function testSetElement()
    {
        $element = new Element();
        $element->setName('foo');
        $element->setValue('bar');

        $elementSet = new ElementSet();
        $elementSet->addElement($element);
        $node = new \Debril\FeedIoBundle\Entity\Node();

        $node->setElementSet($elementSet);

        $count = 0;
        foreach( $node->listElements() as $name ) {
            $count++;
            $this->assertEquals('foo', $name, 'element name must be foo');
        }

        $this->assertEquals(1, $count, 'the node holds only one element');
    }

}
