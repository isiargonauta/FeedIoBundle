<?php

namespace Debril\FeedIoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface; 
use Doctrine\Common\Persistence\ObjectManager; 
use Debril\FeedIoBundle\Entity\Feed; 

class LoadFeedData implements FixtureInterface 

{ 
   /**      
    * {@inheritDoc}      
    */ 
    public function load(ObjectManager $manager) 
    { 
        $feed = new Feed();
        $feed->setLink('http://php.net/feed.atom');
        
        $manager->persist($feed); 
        $manager->flush(); 
    } 
}
