<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 10.04.2016
 * Time: 1:09
 */

namespace genhoi\Controller;

use genhoi\EntityFilter\EntityFilter;
use genhoi\EntityFilter\EntityFilterFactory;
use genhoi\EntityFilter\GroupFilters;
use genhoi\EntityRepository\InMemoryProductRepository;
use genhoi\Helpers\StringBinaryHelper;
use genhoi\RedisFilter\RedisFilterFactory;
use genhoi\RedisFilter\RedisFilterReceiver;
use genhoi\RedisFilter\RedisFilterService;
use genhoi\RedisFilter\RedisKeyNameNormalizer;
use Predis\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class WebController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var RedisFilterReceiver
     */
    protected $redisFilterReceiver;

    /**
     * @var InMemoryProductRepository
     */
    protected $productsRepository;

    /**
     * @var RedisFilterService
     */
    protected $redisFilterService;

    /**
     * @var StringBinaryHelper
     */
    protected $stringBinaryHelper;

    /**
     * WebController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->initServices();
    }

    protected function initServices()
    {
        $this->serializer = SerializerBuilder::create()->build();

        $redisClient = new Client();
        $redisKeyNameNormalizer = new RedisKeyNameNormalizer();
        $keySpace = 'filters:products';
        $redisFilterFactory = new RedisFilterFactory($redisClient, $redisKeyNameNormalizer, $keySpace);
        $this->redisFilterService = new RedisFilterService(
            $redisClient, $redisFilterFactory, $redisKeyNameNormalizer, $keySpace
        );
        $this->redisFilterReceiver = new RedisFilterReceiver(
            $this->redisFilterService, new EntityFilterFactory(), $redisKeyNameNormalizer, $keySpace
        );

        $this->productsRepository = new InMemoryProductRepository();
        $this->stringBinaryHelper = new StringBinaryHelper();
    }

    /**
     * @param EntityFilter[] $entityFilters
     */
    protected function groupFilters($entityFilters)
    {
        $groups = [];
        foreach ($entityFilters as $filter) {
            $groups[$filter->getName()][] = $filter;
        }
        $groupsFilters = [];
        foreach ($groups as $name => $filters) {
            $groupFilters = new GroupFilters();
            $groupFilters->setName($name);
            $groupFilters->setFilters($filters);

            $groupsFilters []= $groupFilters;
        }
        return $groupsFilters;
    }


    public function getAll()
    {
        $allEntityFilters = $this->redisFilterReceiver->getAllEntityFilters();

        $content = [
            'products' => $this->productsRepository->getAll(),
            'groupsFilters' => $this->groupFilters($allEntityFilters)
        ];
        return new Response(
            $this->serializer->serialize($content, 'json')
        );
    }
    
    public function filtration()
    {
        $filters = [];
        $filtersArr = json_decode($this->request->getContent(), true);

        if (count($filtersArr) === 0) {
            return $this->getAll();
        }

        foreach ($filtersArr as $filterArr) {
            $filter = new EntityFilter();
            $filter->setName($filterArr['name']);
            $filter->setValue($filterArr['value']);
            $filters []= $filter;
        }


        $products = [];
        $bitString = $this->redisFilterService->applyEntityFilters($filters);
        $ids = $this->stringBinaryHelper->getPositionsBitInBinaryString($bitString);

        foreach ($ids as $id) {
            $products []= $this->productsRepository->getById($id);
        }
        
        $entityFilters = $this->redisFilterReceiver->getAllEntityFiltersWithCount($bitString);

        foreach ($entityFilters as $responseFilter) {
            foreach ($filters as $requestFilter) {
                if (
                    $responseFilter->getName() === $requestFilter->getName() &&
                    $responseFilter->getValue() === $requestFilter->getValue()
                ) {
                    $responseFilter->setActive(true);
                }
            }
        }

        $content = [
            'products' => $products,
            'groupsFilters' => $this->groupFilters($entityFilters)
        ];

        return new Response(
            $this->serializer->serialize($content, 'json')
        );

    }
}