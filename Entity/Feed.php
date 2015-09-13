<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Entity(repositoryClass="Debril\FeedIoBundle\Entity\FeedRepository")
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
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="public_id", type="string", length=255)
     */
    protected $publicId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="feed", cascade={"persist"})
     * @ORM\OrderBy({"updated"="DESC"})
     * @var Item $items
     */
    protected $items;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Feed
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
