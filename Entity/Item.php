<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use \FeedIo\FeedInterface;
use \FeedIo\Feed\ItemInterface;
use \FeedIo\Feed\Item\MediaInterface;
use \Debril\FeedIoBundle\Entity\Media;

/**
 * Item
 *
 * @ORM\Entity(repositoryClass="Debril\FeedIoBundle\Entity\ItemRepository")
 */
class Item extends Node implements ItemInterface
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

    public function __construct()
    {
        parent::__construct();
        $this->medias = new ArrayCollection();
    }   

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
     * @return FeedInterface
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
        return $this->feed instanceof FeedInterface;
    }

    /**
     * @param FeedInterface $feed
     * @return $this
     */
    public function setFeed(FeedInterface $feed)
    {
        $this->feed = $feed;
        
        return $this;
    }
    
    /**
     * @param  MediaInterface $media
     * @return $this
     */
    public function addMedia(MediaInterface $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getMedias()
    {
        return $this->medias->getIterator();
    }

    /**
     * @return boolean
     */
    public function hasMedia()
    {
        return $this->medias->count() > 0;
    }

    /**
     * @return MediaInterface
     */
    public function newMedia()
    {
        return new Media();
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
