<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 07.04.2016
 * Time: 23:35
 */

namespace genhoi\RedisFilter;


use genhoi\BaseInterface\NormalizerInterface;
use genhoi\EntityFilter\EntityFilterFactory;
use Predis\Client;
use Predis\Collection\Iterator;

class RedisFilterReceiver
{

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var RedisFilterService
     */
    protected $service;

    /**
     * @var EntityFilterFactory
     */
    protected $entityFilterFactory;

    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * @var int
     */
    protected $startPosition;



    public function __construct(
        RedisFilterService $service,
        EntityFilterFactory $entityFilterFactory,
        NormalizerInterface $normalizer,
        string $entityKeySpace
    )
    {
        $this->service = $service;
        $this->entityFilterFactory = $entityFilterFactory;
        $this->normalizer = $normalizer;
        $this->startPosition = strlen( $entityKeySpace . RedisFilterSetting::DELIMITER );
        $this->redisClient = new Client();
    }


    /**
     * @return \genhoi\EntityFilter\EntityFilter[]
     */
    public function getAllEntityFilters()
    {
        $filters = [];
        $allKeysGenerator = $this->service->getAllKeysGenerator();
        foreach ($allKeysGenerator as $key) {
            $filters [] = $this->getEntityFilterFromKey($key);
        }
        return $filters;
    }

    /**
     * @param $key
     * @return \genhoi\EntityFilter\EntityFilter
     */
    protected function getEntityFilterFromKey($key)
    {
        $nameValueFilter = substr($key, $this->startPosition);
        $nameValueArr = explode(RedisFilterSetting::DELIMITER, $nameValueFilter, 2);
        return $this->entityFilterFactory->create( $nameValueArr[0], $nameValueArr[1] );
    }

    /**
     * @param $bitString
     * @return \genhoi\EntityFilter\EntityFilter[]
     */
    public function getAllEntityFiltersWithCount($bitString)
    {
        $filters = [];
        $allKeysGenerator = $this->service->getAllKeysGenerator();

        $tempKeyName = uniqid() . 'tempkeyname';
        $bitStringKeyName = uniqid() . 'tempkeyname';
        $this->redisClient->set($bitStringKeyName, $bitString);

        foreach ($allKeysGenerator as $key) {
            $this->redisClient->bitop('AND', $tempKeyName, $bitStringKeyName, $key);
            $filter = $this->getEntityFilterFromKey($key);
            $count = $this->redisClient->bitcount($tempKeyName);
            $filter->setCount($count);
            $filters [] = $filter;
        }

        $this->redisClient->del([$tempKeyName, $bitStringKeyName]);
        return $filters;
    }

}