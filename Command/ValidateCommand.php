<?php

/**
 * RssAtomBundle
 *
 * @package RssAtomBundle/Command
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Command;

use FeedIo\Reader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateCommand extends ContainerAwareCommand
{

    private $container;

    protected function configure()
    {
        $this
            ->setName('rss-atom:validate')
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
        $text = "will fetch {$url}";

        $parser = $this->getContainer()->get('feedio.parser.rss');
        var_dump($parser);
        $output->writeln($text);
    }

}