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

use Debril\FeedIoBundle\Tests\AppKernel;
use Debril\FeedIoBundle\Adapter\MockStorage;
use FeedIo\FeedIo;
use FeedIo\Adapter\NullClient;
use \Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
 
abstract class CommandTestAbstract extends \PHPUnit_Framework_TestCase 
{ 

    public function setupCommand($name, ContainerAwareCommand $instance)
    {
        $application = $this->getApplication();
        $application->add($instance); 
        
        $command = $application->find($name);
        $command->setContainer($this->getContainer());
        
        return $command;
    }
    
    public function getApplication()
    {
        $kernel = new AppKernel('dev', true);
        $application = new Application($kernel);
        
        return $application;
    }
    
    public function getContainer()
    {
        $container = new Container;
        
        $container->set('feedio', $this->getFeedIoMock());
        $container->set('feedio.storage', new MockStorage);
        
        return $container;
    }
    
    /**
     * @return \FeedIo\FeedIo
     */
    public function getFeedioMock()
    {
        return new \FeedIo\FeedIoMock(
                new NullClient,
                new NullLogger
        );
  
    }
}
