<?php

namespace genhoi\RedisFilter;

use genhoi\Entity\Entity;
use genhoi\BaseInterface\NormalizerInterface;
use Predis\Client;

class RedisFilterFactory
{

    /**
     * @var Client
     */
    protected $redis;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var string
     */
    protected $entityKeySpace;

    /**
     * RedisFiltersFactory constructor.
     * @param Client $redis
     * @param NormalizerInterface $normalizer
     * @param string $entityKeySpace
     */
    public function __construct(Client $redis, NormalizerInterface $normalizer, $entityKeySpace)
    {
        $this->redis = $redis;
        $this->normalizer = $normalizer;
        $this->keySpace = $entityKeySpace;
    }


    public function create(Entity $object)
    {
        foreach ($object as $key => $value) {
            $key = $this->normalizer->normalize([$this->entityKeySpace, $key, $value]);
            $this->redis->setbit($key, $object->getId() - 1, 1);
        }
    }
    
}