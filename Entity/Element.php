<?php

namespace Debril\FeedIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FeedIo\Feed\Node\ElementInterface;

/**
 * Element
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Element implements ElementInterface
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", nullable=true, length=255)
     */
    private $value;

    /**
     * @var array
     *
     * @ORM\Column(name="attributes", type="json_array")
     */
    private $attributes = [];

    /**
     * @ORM\ManyToOne(targetEntity="ElementSet", inversedBy="element_set_id")
     * @ORM\JoinColumn(name="element_set_id", referencedColumnName="id")
     */
    protected $elementSet;

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
     * Set name
     *
     * @param string $name
     * @return Element
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Element
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attributes
     *
     * @param array $attributes
     * @return Element
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function getAttribute($name)
    {
        if ( array_key_exists($name, $this->attributes) ) {
            return $this->attributes[$name];
        }
        
        throw new \UnexpectedValueException("missing attribute {$name}");
    }

    /**
     * @param  string $name
     * @param  string $value
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        
        return $this;
    }

}
