<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Command
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */
 
namespace Debril\FeedIoBundle\Command;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Tester\CommandTester;

class FeedIoCommandTest extends CommandTestAbstract
{ 

    public function testDi() 
    {   
        $command = new SaveCommand;
        $command->setContainer($this->getContainer());
        
        $this->assertInstanceOf('\Feedio\FeedIo', $command->getFeedIo());
        
        
    }
    
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongDi()
    {
        $command = new SaveCommand();
        $command->setContainer(new Container);
        $command->getStorage();
    }
    
}
