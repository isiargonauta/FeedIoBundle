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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class FeedIoCommandAbstract extends ContainerAwareCommand
{

    /**
     * @return \FeedIo\FeedIo
     */
    protected function getFeedIo()
    {
        return $this->getContainer()->get('feedio');
    }

}