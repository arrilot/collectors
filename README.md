[![Latest Stable Version](https://poser.pugx.org/arrilot/collectors/v/stable.svg)](https://packagist.org/packages/arrilot/collectors/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/collectors.svg?style=flat)](https://packagist.org/packages/Arrilot/collectors)
[![Build Status](https://img.shields.io/travis/arrilot/collectors/master.svg?style=flat)](https://travis-ci.org/arrilot/collectors)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/collectors/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/collectors/)

# PHP Collectors (In development)

## Introduction

Collectors scan across given fields in items/collections for ids and fetch detailed data from database or another storage

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
    public function getList(array $ids)
    {
        ...
    }
}
```

Example
```php
    $elements = [
        ['id' => 1, 'files' => 1],
        ['id' => 2, 'files' => [2, 1]],
    ];
    
    $item = [
        'id' => 3,
        'another_files' => 3
    ];
    
    $collector = new FooCollector();
    $collector->fromCollection($elements, 'files');
    $collector->fromItem($item, 'another_files'); 
    // You can pass several fields as array  - $collector->fromItem($item, ['field_1', 'field_2']);
    $files = $collector->performQuery();
    
    var_dump($files);

    // result
    /*
        array:2 [▼
          1 => array:3 [▼
              "id" => 1
              "name" => "avatar.png",
              "module" => "main",
          ]
          2 => array:3 [▼
              "id" => 2
              "name" => "test.png",
              "module" => "main",
          ],
          3 => array:3 [▼
               "id" => 3
               "name" => "test2.png",
               "module" => "main",
          ],
        ]
    */
```

You can pass `select` to `getlist` like that:
```php
$files = $collector->select(['id', 'name'])->performQuery();
// $this->select is ['id', 'name'] in `->getList()` and you can implement logic handling it.
```

Same is true for an additional filter.
```php
$collector->where(['active' => 1])->performQuery();
// $this->where is ['active' => 1]
```

You can use dot notation to locate a field, e.g
```php
$collector->fromItem($item, 'properties.files');
```

## Bridge packages

- [Bitrix (russian)](https://github.com/arrilot/bitrix-collectors)
