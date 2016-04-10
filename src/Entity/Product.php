<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 07.04.2016
 * Time: 20:54
 */

namespace genhoi\Entity;


class Product extends Entity
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $photo;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * Product constructor.
     * @param int $id
     * @param string $name
     * @param float $price
     * @param string $photo
     * @param array $attributes
     */
    public function __construct($id, $name, $price, $photo, array $attributes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->photo = $photo;
        $this->attributes = $attributes;
    }

    public function getIterator() {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}