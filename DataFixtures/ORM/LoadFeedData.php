<?php

namespace Debril\FeedIoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface; 
use Doctrine\Common\Persistence\ObjectManager; 
use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Entity\Item;

class LoadFeedData implements FixtureInterface 

{ 
   /**      
    * {@inheritDoc}      
    */ 
    public function load(ObjectManager $manager) 
    { 
         $this->addPublicFeed($manager);
         $this->addExternalFeed($manager);
    }
    
    protected function addPublicFeed(ObjectManager $manager)
    {
        $feed = new Feed();
        $feed->setLink('http://localhost:8000/atom/1');
        $feed->setTitle('FeedIoBundle : the bundle to publish and read feeds with Symfony');
        $feed->setType(Feed::TYPE_PUBLIC);
        $feed->setLastModified(new \DateTime());

        $item = new Item();
        $item->setTitle('this is the sample feed');
        $item->setDescription("thank you for using FeedIoBundle.
        You can visit http://debril.org/category/feed-io to get the project's latest news.
        ");
        $item->setLastModified(new \DateTime());
        $item->setLink('http://localhost:8000/item/edit/1');

        $feed->add($item);
        $manager->persist($feed); 
        $manager->flush(); 
    }
    
    protected function addExternalFeed(ObjectManager $manager)
    {
        $feed = new Feed();
        $feed->setLink('http://php.net/feed.atom');
        $feed->setTitle('PHP : Hypertext Preprocessor');
        $feed->setType(Feed::TYPE_EXTERNAL);
        $feed->setLastModified(new \DateTime());

        $manager->persist($feed); 
        $manager->flush(); 
    }
}
