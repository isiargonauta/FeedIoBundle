<?php

namespace Debril\FeedIoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;

trait CommandTrait
{

    protected static $application;


    public function createDb()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
    }
    
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            static::bootKernel(array());
            self::$application = new \Symfony\Bundle\FrameworkBundle\Console\Application(static::$kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
    
    
    public function dropDb()
    {
        self::runCommand('doctrine:database:drop --force');
    }
    
} 