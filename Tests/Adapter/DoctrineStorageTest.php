<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Adapter
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */
 
namespace Debril\FeedIoBundle\Adapter;

use Symfony\Component\DependencyInjection\Container;
use \Doctrine\Bundle\DoctrineBundle\Registry;

class DoctrineStorageTest extends \PHPUnit_Framework_TestCase
{ 

    /**
     * @var DoctrineStorage
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new DoctrineStorage(
            new Registry(new Container, array(), array(), 'default', 'default')
        );
    }
 
    public function testRepositoryName()
    {
        $name = 'foo';
        
        $this->object->setRepositoryName($name);
        
        $this->assertEquals($name, $this->object->getRepositoryName());
    }
    
}
