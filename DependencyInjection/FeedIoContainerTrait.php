<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/DependencyInjection
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\FeedIoBundle\DependencyInjection;

use Debril\FeedIoBundle\Exception\InvalidStorageException;
use \Debril\FeedIoBundle\Adapter\StorageInterface;

trait FeedIoContainerTrait
{

    /**
     * @return \FeedIo\FeedIo
     */
    public function getFeedIo()
    {
        return $this->getContainer()->get('feedio');
    }

    /**
     * @return \Debril\FeedIoBundle\Adapter\StorageInterface
     * @throws \InvalidArgumentException
     */
    public function getStorage()
    {
        $storage = $this->getContainer()->get('feedio.storage');
        if( ! $storage instanceof StorageInterface ) {
            throw new InvalidStorageException("feedio.storage is not a StorageInterface : " . get_class($storage));
        }

        return $storage;
    }

    /**
     * @return Container
     */
    abstract protected function getContainer();

}
