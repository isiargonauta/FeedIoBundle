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

use Symfony\Component\Console\Tester\CommandTester;
 
class ValidateCommandTest extends CommandTestAbstract 
{ 

    public function testExecute() 
    {   
        $command = $this->setupCommand('feed-io:validate', new ValidateCommand());
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
                array(
                    'command' => $command->getName(),
                    'url' => 'http://localhost',
                    )
        );
        
    }
    
}
