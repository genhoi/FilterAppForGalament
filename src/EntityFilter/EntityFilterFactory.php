<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 08.04.2016
 * Time: 0:22
 */

namespace genhoi\EntityFilter;


class EntityFilterFactory
{
    
    public function create($name, $value)
    {
        $filter = new EntityFilter();
        $filter->setName($name);
        $filter->setValue($value);
        return $filter;
    }

}