<?php

namespace genhoi\BaseInterface;

interface NormalizerInterface 
{
    public function normalize(array $strings): string;
}