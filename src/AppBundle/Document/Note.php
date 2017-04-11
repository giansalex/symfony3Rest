<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 09/04/2017
 * Time: 17:03
 */

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Note
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    protected $value;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    protected $color;



    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return self
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Get color
     *
     * @return string $color
     */
    public function getColor()
    {
        return $this->color;
    }
}
