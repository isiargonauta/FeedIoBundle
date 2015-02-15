<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Provider
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 * creation date : 2013 / 03 / 31
 * fork date : 2015 / 02 / 15
 *
 */

namespace Debril\FeedIoBundle\Provider;

use FeedIo\Feed;
use FeedIo\Feed\Item;

class MockProvider implements FeedProviderInterface
{

    /**
     * @param  array                 $options
     * @return Feed
     * @throws FeedNotFoundException
     */
    public function getFeedContent(array $options)
    {
        $feed = new Feed();

        $id = array_key_exists('id', $options) ? $options['id'] : null;

        if ($id === 'not-found')
            throw new \InvalidArgumentException();

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

}
