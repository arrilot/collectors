<?php

namespace Arrilot\Tests\Tankers\Stubs;

use Arrilot\Tankers\Tanker;
use RuntimeException;

class FooTanker extends Tanker
{
    /**
     * Fetch data for given ids.
     *
     * @param array $ids
     *
     * @return array
     */
    public function fetch(array $ids)
    {
        if (!$ids) {
            throw new RuntimeException('This line must never be reached.');
        }

        $select = is_null($this->select) ? ['id', 'foo'] : $this->select;

        $data = [];
        foreach ($ids as $id) {
            $data[$id] = array_filter([
                'id'  => $id,
                'foo' => 'bar',
            ], function ($key) use ($select) {
                return in_array($key, $select);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $data;
    }
}
