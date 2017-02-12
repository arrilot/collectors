[![Latest Stable Version](https://poser.pugx.org/arrilot/collectors/v/stable.svg)](https://packagist.org/packages/arrilot/collectors/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/collectors.svg?style=flat)](https://packagist.org/packages/Arrilot/collectors)
[![Build Status](https://img.shields.io/travis/arrilot/collectors/master.svg?style=flat)](https://travis-ci.org/arrilot/collectors)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/collectors/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/collectors/)

# PHP Collectors

## Introduction

Collectors scan across given fields in items/collections for ids and fetch detailed data from database ot

## Installation

`composer require arrilot/collectors`

## Usage

First of all you need to create your own collector class.

```php

use Arrilot\Collectors\Collector;

class FooCollector extends Collector
{
    /**
     * Get data for given ids.
     *
     * @param array $ids
     * @return array
     */
    public function getByIds(array $ids)
    {
        ...
    }
}
```

Example 1
```php
    $elements = [
        ['id' => 1, 'files' => 1],
        ['id' => 2, 'files' => [2, 1]],
    ];
    
    $tanker = new FooCollector();
    $files = $tanker->collection($elements)->fields('files')->get();
    var_dump($files);

    // result
    /*
        array:2 [▼
          1 => array:2 [▼
              "id" => 1
              "name" => "avatar.png"
          ]
          2 => array:2 [▼
              "id" => 2
              "name" => "test.png"
          ]
        ]
    */
```

Example 2
```php
    $elements = [
        ['id' => 1, 'files' => 1],
        ['id' => 2, 'files' => [2, 1]],
    ];
    
    $tanker = new FooCollector();
    $tanker->collection($elements)->fields('files')->fill();
    var_dump($elements);

    // result
    /*
        array:2 [▼
            array:3 [▼
                "id" => 1
                "files" => 1,
                "files_data" => array:2 [▼
                  "id" => 2
                  "name" => "test.png"
                ]
            ]
            array:3 [▼
                "id" => 2
                "files" => [2, 1],
                "files_data" => array:2 [▼
                    array:2 [▼
                        "id" => 2
                        "name" => "avatar.png"
                    ],
                    array:2 [▼
                        "id" => 1
                        "name" => "avatar.png"
                    ]
                ]
            ]
        ]
    */
```

## Bridge packages

- [Bitrix (russian)](https://github.com/arrilot/bitrix-collectors)
