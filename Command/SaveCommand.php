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

use FeedIo\Reader;
use \Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SaveCommand extends ContainerAwareCommand
{

    use FeedIoCommandTrait;
    
    protected function configure()
    {
        $this
            ->setName('feed-io:save')
            ->setDescription('save a news feed into storage')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Please provide the feed\' URL'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        try {
            $result = $this->getFeedIo()->read($url);
            $this->getStorage()->save($result->getFeed());
        } catch (\Exception $e) {
            $output->writeln("issues detected : {$e->getMessage()}");
        }

    }

}