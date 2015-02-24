<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Adapter
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\FeedIoBundle\Adapter;

use \FeedIo\FeedInterface;

interface StorageInterface
{

    /**
     * @param $id
     * @return \FeedIo\FeedInterface
     */
    public function getFeed($id);

    /**
     * @param \FeedIo\FeedInterface $feed
     * @return $this
     */
    public function save(FeedInterface $feed);

}
