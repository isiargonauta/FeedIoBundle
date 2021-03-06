<?php

namespace Debril\FeedIoBundle\Entity;

use \FeedIo\FeedInterface;
use \FeedIo\Feed\ItemInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Feed
 *
 * @ORM\Entity(repositoryClass="Debril\FeedIoBundle\Entity\FeedRepository")
 */
class Feed extends Node implements FeedInterface
{

    /**
     * feeds fetched from external websites
     */
    const TYPE_EXTERNAL = 1;

    /**
     * feeds published across the internet
     */
    const TYPE_PUBLIC = 2;
    
    /**
     * private feeds
     */
    const TYPE_PRIVATE = 3;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="smallint", options={"default":1})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="feed", cascade={"persist"})
     * @ORM\OrderBy({"publishedAt"="DESC"})
     * @var ArrayCollection $items
     */
    protected $items;
        
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", nullable=true)
     */
    protected $url;
    
    protected $itemIterator;
    
    /**
     * @var array $availableTypes
     */
    private $availableTypes = [            
            self::TYPE_EXTERNAL => 'external',
            self::TYPE_PUBLIC => 'public',
            self::TYPE_PRIVATE => 'private',
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getTitle();
    }
        
    /**
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }

    /**
     * Get a new Item instance
     *
     * @return \Debril\FeedIoBundle\Entity\Item
     */
    public function newItem()
    {
        return new Item();
    }
        
    /**
     * Add a new item
     *
     * @param ItemInterface $item
     * @return $this
     */
    public function add(ItemInterface $item)
    {
        $this->items[] = $item;
        $item->setFeed($this);
        
        return $this;
    }

    /**
     * Tell if the given type is supportes
     *
     * @param integer $type
     * @return boolean
     */
    public function isValidType($type)
    {
        return array_key_exists($type, $this->getAvailableTypes());
    }
     
    /**
     * Get getAvailableTypes
     *
     * @return array 
     */
    public function getAvailableTypes()
    {
        return $this->availableTypes;
    }
    
    /**
     * Set type
     *
     * @param integer $type
     * @return $this
     */
    public function setType($type)
    {
        if ( ! $this->isValidType($type) ) {
            throw new \UnexpectedValueException($type);
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return ArrayIterator
     */
    public function getItemIterator()
    {
        if ( is_null($this->itemIterator) ) {
            $this->itemIterator = $this->items->getIterator();
        }
        
        return $this->itemIterator;
    } 

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->getItemIterator()->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return $this->getItemIterator()->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->getItemIterator()->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return $this->getItemIterator()->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->getItemIterator()->rewind();
    }

}
