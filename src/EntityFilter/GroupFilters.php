<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 10.04.2016
 * Time: 22:39
 */

namespace genhoi\EntityFilter;


class GroupFilters
{

    protected $name;

    protected $filters;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

}