<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 07.04.2016
 * Time: 21:27
 */

namespace genhoi\RedisFilter;


use genhoi\BaseInterface\NormalizerInterface;
use genhoi\Entity\Entity;

use genhoi\EntityFilter\EntityFilter;
use genhoi\Helpers\StringBinaryHelper;
use Predis\Client;
use Predis\Collection\Iterator;

class RedisFilterService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RedisFilterFactory
     */
    protected $filtersFactory;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var string
     */
    protected $patternAllKey;

    /**
     * @var StringBinaryHelper
     */
    protected $helper;

    protected $entityKeySpace;

    /**
     * RedisFilterService constructor.
     * @param Client $client
     * @param RedisFilterFactory $filtersFactory
     * @param NormalizerInterface $normalizer
     * @param int $entityKeySpace
     */
    public function __construct(
        Client $client,
        RedisFilterFactory $filtersFactory,
        NormalizerInterface $normalizer,
        $entityKeySpace
    )
    {
        $this->client = $client;
        $this->normalizer = $normalizer;
        $this->filtersFactory = new RedisFilterFactory($client, $normalizer, $entityKeySpace);
        $this->patternAllKey = $normalizer->normalize([$entityKeySpace, '*']);
        $this->entityKeySpace = $entityKeySpace;
        $this->helper = new StringBinaryHelper();
    }

    /**
     * @param Entity[]|\Generator $entities
     */
    public function buildFilters($entities)
    {
        foreach ($entities as $entity) {
            $this->filtersFactory->create($entity);
        }
    }

    public function getKeysGenerator($pattern)
    {
        foreach (new Iterator\Keyspace($this->client, $pattern) as $key) {
            yield $key;
        }
    }

    public function getAllKeysGenerator()
    {
        yield from $this->getKeysGenerator($this->patternAllKey);
    }

    public function clearAll()
    {
        $allKeys = $this->client->keys($this->patternAllKey);
        if (count($allKeys) !== 0) {
            $this->client->del($allKeys);
        }
    }

    public function clearEntityFilter(Entity $entity)
    {
        foreach ($this->getAllKeysGenerator() as $key) {
            $this->client->setbit($key, $entity->getId(), 0);
        }
    }

    /**
     * @param EntityFilter[] $filters
     */
    public function applyEntityFilters($filters)
    {
        $filterKeys = [];

        if (count($filters) === 0) {
            return [];
        }

        foreach ($filters as $filter) {
            $filterKeys []= $this->normalizer->normalize([$this->entityKeySpace ,$filter->getName(), $filter->getValue()]);
        }
        $tempKeyNameForResult = uniqid() . 'tempkeyname';

        $this->client->bitop('AND', $tempKeyNameForResult, $filterKeys[0], $filterKeys[0]);
        foreach ($filterKeys as $key) {
            $this->client->bitop('AND', $tempKeyNameForResult, $tempKeyNameForResult, $key);
        }

        $resultBitString = $this->client->get($tempKeyNameForResult);
        $this->client->del([$tempKeyNameForResult]);

        return $resultBitString;
    }
    
}