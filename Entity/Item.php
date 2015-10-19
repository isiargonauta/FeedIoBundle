<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Item
 *
 * @ORM\Entity(repositoryClass="Debril\FeedIoBundle\Entity\ItemRepository")
 */
class Item extends Node
{

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Feed", inversedBy="items")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id")
     */
    protected $feed;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="item", cascade={"persist"})
     * @var Media $medias
     */
    protected $medias;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @return boolean
     */
    public function hasFeed()
    {
        return $this->feed instanceof Feed;
    }

    /**
     * @param Feed $feed
     * @return $this
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;
        
        return $this;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Item
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

}
