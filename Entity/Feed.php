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
     * @var boolean
     *
     * @ORM\Column(name="external", type="boolean")
     */
    private $external;

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
    
    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime;
    }
    /**
     * Set external
     *
     * @param boolean $external
     * @return Feed
     */
    public function setExternal($external)
    {
        $this->external = $external;

        return $this;
    }

    /**
     * Get external
     *
     * @return boolean 
     */
    public function getExternal()
    {
        return $this->external;
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
     * @return Item
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
