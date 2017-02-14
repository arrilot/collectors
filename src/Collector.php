<?php

namespace Arrilot\Collectors;

use Illuminate\Support\Arr;

abstract class Collector
{
    /**
     * Ids that are collected from sources.
     *
     * @var array
     */
    protected $ids = [];

    /**
     * Fields that should be selected.
     *
     * @var mixed
     */
    protected $select = null;

    /**
     * Additional filter.
     *
     * @var mixed
     */
    protected $where = null;

    /**
     * Data keyed by id.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Get data for given ids.
     *
     * @param array $ids
     * @return array
     */
    abstract protected function getList(array $ids);

    /**
     * Add a collection source.
     *
     * @param $collection
     * @param mixed $fields
     * @return $this
     */
    public function scanCollection($collection, $fields)
    {
        $fields = (array) $fields;
        foreach ($collection as $item) {
            $this->collectIdsFromItem($item, $fields);
        }

        return $this;
    }

    /**
     * Add an item source.
     *
     * @param $item
     * @param mixed $fields
     * @return $this
     */
    public function scanItem($item, $fields)
    {
        $fields = (array) $fields;
        $this->collectIdsFromItem($item, $fields);

        return $this;
    }

    /**
     * Setter for select.
     *
     * @param mixed $select
     * @return $this
     */
    public function select($select)
    {
        $this->select = $select;

        return $this;
    }

    /**
     * Setter for where.
     *
     * @param mixed $where
     * @return $this
     */
    public function where($where)
    {
        $this->where = $where;

        return $this;
    }

    /**
     * Perform main query.
     *
     * @return array
     */
    public function performQuery()
    {
        if (!$this->ids) {
            return [];
        }

        return $this->getList($this->getIdsWithoutDuplications());
    }
    
    /**
     * Collect ids from source and add them to $this->ids
     *
     * @param $item
     * @param array $fields
     */
    protected function collectIdsFromItem($item, array $fields)
    {
        foreach ($fields as $field) {
            $ids = Arr::get($item, $field, []);
            if (is_object($ids) && method_exists($ids, 'toArray')) {
                $ids = $ids->toArray();
            }

            foreach ( (array) $ids as $id) {
                if ((int) $id) {
                    $this->ids[] = (int) $id;
                }
            }
        }
    }

    /**
     * A faster alternative to array_unique.
     *
     * @return array
     */
    protected function getIdsWithoutDuplications()
    {
        return array_flip(array_flip($this->ids));
    }
}
