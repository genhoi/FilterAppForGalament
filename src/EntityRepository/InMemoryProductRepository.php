<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 09.04.2016
 * Time: 23:05
 */

namespace genhoi\EntityRepository;


use genhoi\Entity\Product;

class InMemoryProductRepository
{

    private $products;
    public function __construct()
    {
        $this->products = [
            1 => new Product(
                1,
                'APPLE iphone 6',
                249.3,
                'http://mskapp.ru/files/dev/iphone6WITE1.jpg',
                json_decode(
                    '{
                    "os": "IOS5",
                    "gps": true,
                    "bluetooth": false,
                    "wifi": "abgn",
                    "color": "green",
                    "company": "APPLE"
                    }', true
                )
            ),
            2 => new Product(
                2,
                'ETERNIS X4',
                100,
                'http://s.thestreet.com/files/tsc/v2008/photos/contrib/uploads/nexus6p-dual_600x400.jpg',
                json_decode(
                    '{
                    "os": "Andoid 4.2",
                    "gps": true,
                    "bluetooth": false,
                    "wifi": "abgn",
                    "color": "green",
                    "company": "ETERNIS"
                  }', true
                )
            ),
            3 => new Product(
                3,
                'ORGANICA A3470',
                89,
                'http://s.thestreet.com/files/tsc/v2008/photos/contrib/uploads/nexus6p-dual_600x400.jpg',
                json_decode(
                    '{
                    "os": "Andoid 5",
                    "gps": false,
                    "bluetooth": 3,
                    "wifi": "a",
                    "color": "brown",
                    "company": "ORGANICA"
                  }', true
                )
            ),
            4 => new Product(
                4,
                'ORGANICA S5640',
                346,
                'http://s.thestreet.com/files/tsc/v2008/photos/contrib/uploads/nexus6p-dual_600x400.jpg',
                json_decode(
                    '{
                    "os": "Andoid 5",
                    "gps": false,
                    "bluetooth": 3,
                    "wifi": "a",
                    "color": "brown",
                    "company": "ORGANICA"
                  }', true
                )
            ),
            5 => new Product(
                5,
                'APPLE iphone 4',
                346,
                'http://mskapp.ru/files/dev/iphone6WITE1.jpg',
                json_decode(
                    '{
                    "os": "IOS6",
                    "gps": false,
                    "bluetooth": 2,
                    "wifi": "a",
                    "color": "green",
                    "company": "APPLE"
                  }', true
                )
            ),
            6 => new Product(
                6,
                'COREPAN S7',
                474,
                'http://mskapp.ru/files/dev/iphone6WITE1.jpg',
                json_decode(
                    '{
                    "os": "Andoid 4.2",
                    "gps": true,
                    "bluetooth": 3,
                    "wifi": "abgn",
                    "color": "green",
                    "company": "COREPAN"
                  }', true
                )
            ),
        ];
    }
    public function getAll()
    {
        return $this->products;
    }
    public function getById($id)
    {
        return $this->products[$id];
    }

}