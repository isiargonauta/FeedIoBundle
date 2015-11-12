<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\Feed\Node\ElementIterator;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Node
 *
 * @ORM\MappedSuperclass 
 * @HasLifecycleCallbacks
 */
class Node extends \FeedIo\Feed\Node
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected $link;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastModified", type="datetime")
     */
    protected $lastModified;

    /**
     * @var string
     *
     * @ORM\Column(name="public_id", type="string", length=255, nullable=true)
     */
    protected $publicId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="ElementSet", cascade={"persist"})
     * @ORM\JoinColumn(name="element_set_id", referencedColumnName="id")
     * @var ElementSet $elementSet
     */
    protected $elementSet;
    
    public function __construct()
    {
        parent::__construct();
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
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ElementSet
     */
    public function getElementSet()
    {
        if ( is_null($this->elementSet) ) {
            $this->setElementSet(new ElementSet);
        }

        return $this->elementSet;
    }

    /**
     * @param ElementSet $elementSet
     */
    public function setElementSet($elementSet)
    {
        $this->elementSet = $elementSet;
    }

    /**
     * returns a new ElementInterface
     *
     * @return \FeedIo\Feed\Node\ElementInterface
     */
    public function newElement()
    {
        return new Element();
    }

    /**
     * returns the ElementIterator to iterate over ElementInterface instances called $name
     *
     * @param  string $name element name
     * @return \FeedIo\Feed\Node\ElementIterator
     */
    public function getElementIterator($name)
    {
        return new ElementIterator($this->getElementSet()->getElements()->getIterator(), $name);
    }

    /**
     * adds $element to the object's attributes
     *
     * @param  ElementInterface $element
     * @return $this
     */
    public function addElement(ElementInterface $element)
    {
        $this->getElementSet()->addElement($element);

        return $this;
    }

    /**
     * Returns all the item's elements
     *
     * @return ElementInterface[]
     */
    public function getAllElements()
    {
        return $this->getElementSet()->getElements()->getIterator();
    }

    /**
     * Returns the item's elements tag names
     *
     * @return \Iterator
     */
    public function listElements()
    {
        foreach ($this->getAllElements() as $element) {
            yield $element->getName();
        }
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
     * Set modifiedAt
     *
     * @PrePersist
     * @PreUpdate
     *
     * @return $this
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
}
