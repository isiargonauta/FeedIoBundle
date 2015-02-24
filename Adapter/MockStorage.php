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
use \FeedIo\Feed;
use \FeedIo\Feed\Item;

class MockStorage implements StorageInterface
{
    /**
     * @param $id
     * @return \FeedIo\FeedInterface
     */
    public function getFeed($id)
    {
        $feed = new Feed();

        if ($id === 'not-found') {
            throw new FeedNotFoundException();
        }

        $feed->setPublicId($id);

        $feed->setTitle('thank you for using RssAtomBundle');
        $feed->setDescription('this is the mock FeedContent');
        $feed->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/');
        $feed->setLastModified(new \DateTime());

        $item = new Item();

        $item->setPublicId('1');
        $item->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/somelink');
        $item->setTitle('This is an item');
        $item->setDescription('this stream was generated using the MockProvider class');
        $item->setLastModified(new \DateTime());

        $feed->add($item);

        return $feed;
    }

    /**
     * @param \FeedIo\FeedInterface $feed
     * @return $this
     */
    public function save(FeedInterface $feed)
    {
        return $this;
    }

}