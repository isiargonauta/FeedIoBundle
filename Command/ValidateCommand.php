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

class ValidateCommand extends FeedIoCommand
{

    protected function configure()
    {
        $this
            ->setName('feed-io:validate')
            ->setDescription('validate a news feed')
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
        $feedIo = $this->getFeedIo();

        $output->writeln("fetching {$url}");

        try {
            $result = $feedIo->read($url);
            $output->writeln("no warning for {$result->getFeed()->getTitle()}");
            return 0;
        } catch (\Exception $e) {
            $output->writeln("issue detected : {$e->getMessage()}");
            return 1;
        }

    }

}