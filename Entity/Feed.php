<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
/**
 * Feed
 *
 * @ORM\Entity(repositoryClass="Debril\FeedIoBundle\Entity\FeedRepository")
 * @HasLifecycleCallbacks
 */
class Feed extends Node
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="feed", cascade={"persist"})
     * @ORM\OrderBy({"updated"="DESC"})
     * @var Item $items
     */
    protected $items;
    
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
        $this->createdAt = new \DateTime;
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
     * @return Feed
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
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Feed
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
     * Set modifiedAt
     *
     * @PrePersist
     * @PreUpdate
     *
     * @return Feed
     */
    public function updateModifiedAt()
    {
        $this->modifiedAt = new \DateTime;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}
