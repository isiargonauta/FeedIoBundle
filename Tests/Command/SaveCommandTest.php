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

class SaveCommandTest extends CommandTestAbstract 
{ 

    public function testExecute() 
    {   
        $command = $this->setupCommand('feed-io:save', new SaveCommand());
        $commandTester = new CommandTester($command);
        
        $out = $commandTester->execute(
                array(
                    'command' => $command->getName(),
                    'url' => 'http://localhost',
                    )
        );
        
        $feedIoMock = $command->getFeedIo();
        $this->assertEquals(1, $feedIoMock->getReadCount());
        $storageMock = $command->getStorage();
        $this->assertEquals(1, $storageMock->getSaveCount());
        $this->assertEquals(0, $out);
    }
    
}
