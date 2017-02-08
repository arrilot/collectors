<?php

namespace Arrilot\Tests\DotEnv;

use Arrilot\Tests\Tanker\Stubs\FooTanker;
use PHPUnit_Framework_TestCase;

class DotEnvTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_fill_a_basic_collection()
    {
        $tanker = new FooTanker();
        $collection = [
          [
              'file' => 2,
          ],
          [
              'file' => 1,
          ],
        ];
    
        $tanker->collection($collection)->fields('file')->fill();


        $expected = [
            [
                'file' => 2,
                'file_data' => [
                    'id' => 2,
                    'foo' => 'bar'
                ]
            ],
            [
                'file' => 1,
                'file_data' => [
                    'id' => 1,
                    'foo' => 'bar'
                ]
            ],
        ];
        
        $this->assertEquals($expected, $collection);
    }
    
    public function test_it_can_fill_a_collection_with_empty_or_null_field()
    {
        $tanker = new FooTanker();
        $collection = [
            [
                'file' => 2,
            ],
            [
                'file' => '',
            ],
            [
                'file' => null,
            ],
        ];
        
        $tanker->collection($collection)->fields('file')->fill();
        
        
        $expected = [
            [
                'file' => 2,
                'file_data' => [
                    'id' => 2,
                    'foo' => 'bar'
                ]
            ],
            [
                'file' => '',
                'file_data' => []
            ],
            [
                'file' => '',
                'file_data' => []
            ],
        ];
        
        $this->assertEquals($expected, $collection);
    }
    
    public function test_it_can_fill_a_collection_with_multivalue_fields()
    {
        $tanker = new FooTanker();
        $collection = [
            [
                'file' => 2,
            ],
            [
                'file' => [3, 4],
            ]
        ];
        
        $tanker->collection($collection)->fields('file')->fill();
        
        $expected = [
            [
                'file' => 2,
                'file_data' => [
                    'id' => 2,
                    'foo' => 'bar'
                ]
            ],
            [
                'file' => [3, 4],
                'file_data' => [
                    [
                        'id' => 3,
                        'foo' => 'bar'
                    ],
                    [
                        'id' => 4,
                        'foo' => 'bar'
                    ]
                ]
            ],
        ];
        
        $this->assertEquals($expected, $collection);
    }
    
    public function test_it_can_fill_a_collection_with_multiple_fields()
    {
        $tanker = new FooTanker();
        $collection = [
            [
                'file' => 2,
                'file2' => 3,
            ],
            [
                'file' => [3, 4],
                'file2' => [1, ''],
            ]
        ];
        
        $tanker->collection($collection)->fields('file');
        $tanker->collection($collection)->fields('file2');
        $tanker->fill();
        
        $expected = [
            [
                'file' => 2,
                'file2' => 3,
                'file_data' => [
                    'id' => 2,
                    'foo' => 'bar'
                ],
                'file2_data' => [
                    'id' => 3,
                    'foo' => 'bar'
                ]
            ],
            [
                'file' => [3, 4],
                'file2' => [1, ''],
                'file_data' => [
                    [
                        'id' => 3,
                        'foo' => 'bar'
                    ],
                    [
                        'id' => 4,
                        'foo' => 'bar'
                    ]
                ],
                'file2_data' => [
                    [
                        'id' => 1,
                        'foo' => 'bar'
                    ],
                    []
                ]
            ],
        ];
        
        $this->assertEquals($expected, $collection);
    }
}
