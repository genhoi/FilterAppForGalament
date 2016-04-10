<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 10.04.2016
 * Time: 0:00
 */

namespace genhoi\Controller;

use genhoi\EntityRepository\InMemoryProductRepository;
use genhoi\Helpers\StringBinaryHelper;
use genhoi\RedisFilter\RedisFilterFactory;
use genhoi\RedisFilter\RedisFilterService;
use genhoi\RedisFilter\RedisKeyNameNormalizer;
use Predis\Client;

class ConsoleController
{

    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * CommandController constructor.
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }


    public function populateFilters()
    {
        $redisKeyNameNormalizer = new RedisKeyNameNormalizer();
        $keySpace = 'filters:products';
        $redisFilterFactory = new RedisFilterFactory($this->redisClient, $redisKeyNameNormalizer, $keySpace);
        $redisFilterService = new RedisFilterService($this->redisClient, $redisFilterFactory, $redisKeyNameNormalizer, $keySpace);

        $productsRepository = new InMemoryProductRepository();

        $redisFilterService->clearAll();
        $redisFilterService->buildFilters($productsRepository->getAll());
    }

    public function getBinaryStringFromKey($key)
    {
        $string = $this->redisClient->get($key);

        $binaryConverter = new StringBinaryHelper();
        echo $binaryConverter->convertStringToBinaryString($string) . PHP_EOL;
    }

}