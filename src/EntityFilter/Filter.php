<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 10.04.2016
 * Time: 23:03
 */

namespace genhoi\EntityFilter;


class Filter
{
    protected $value;
    
    protected $count;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
    
    
}