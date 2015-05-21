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
use FeedIo\Adapter\NullClient;
use \Psr\Log\NullLogger;

class ValidateCommandTest extends CommandTestAbstract 
{ 

    public function testExecute() 
    {   
        $command = $this->setupCommand('feed-io:validate', new ValidateCommand());
        $commandTester = new CommandTester($command);
        
        $out = $commandTester->execute(
                array(
                    'command' => $command->getName(),
                    'url' => 'http://localhost',
                    )
        );
        
        $feedIoMock = $command->getFeedIo();
        $this->assertEquals(1, $feedIoMock->getReadCount());
        $this->assertEquals(0, $out);
    }
    
    public function testExecuteWithException()
    {
        $command = $this->setupCommand('feed-io:validate', new ValidateCommand());
        
        $command->setContainer($this->getDoomedContainer());
        $commandTester = new CommandTester($command);
        
        $out = $commandTester->execute(
                array(
                    'command' => $command->getName(),
                    'url' => 'http://localhost',
                    )
        );
        
        $this->assertNotEquals(0, $out);
    }
    
    protected function getDoomedContainer()
    {
        $mock = $this->getMockForAbstractClass('\FeedIo\FeedIo', 
                    array(
                        new NullClient,
                        new NullLogger
                    )
                );
                
        $mock->expects($this->any())->method('read')->will($this->throwException(new \Exception));
    
        $container = new Container;
        $container->set('feedio', $mock);
        
        return $container;
    }
    
}
