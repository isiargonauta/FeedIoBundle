<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FeedIo\Feed\Node\ElementInterface;

/**
 * ElementSet
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ElementSet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Element", mappedBy="elementSet", cascade={"persist"})
     * @var array[Element] $elements
     */
    private $elements;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
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
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \Iterator
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param ElementInterface $element
     * @return $this
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements[] = $element;

        return $this;
    }

}
