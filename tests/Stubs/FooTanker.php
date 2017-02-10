<?php

namespace Arrilot\Tests\Tankers\Stubs;

use Arrilot\Tankers\Tanker;

class FooTanker extends Tanker
{
    /**
     * Fetch data for given ids.
     *
     * @param array $ids
     * @return array
     */
    public function fetch(array $ids)
    {
        $data = [];
        foreach ($ids as $id) {
            $data[$id] = [
                'id' => $id,
                'foo' => 'bar',
            ];
        }
        
        return $data;
    }
}
