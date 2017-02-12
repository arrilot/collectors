<?php

namespace Arrilot\Tankers;

abstract class Tanker
{
    /**
     * Collections to be filled.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Fields that should be filled in each corresponding collection.
     *
     * @var array
     */
    protected $fields = [];

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
     * Field suffix.
     *
     * @var string
     */
    protected $suffix = '_data';

    /**
     * Get data for given ids.
     *
     * @param array $ids
     *
     * @return array
     */
    abstract protected function getByIds(array $ids);

    /**
     * Add collection.
     *
     * @param $collection
     *
     * @return $this
     */
    public function collection(&$collection)
    {
        $this->collections[] = &$collection;

        return $this;
    }

    public function item(&$item)
    {
        $this->collections[] = [&$item];

        return $this;
    }

    /**
     * Setter for suffix.
     *
     * @param string $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
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
     * Add fields.
     *
     * @param $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        if (!is_array($fields)) {
            $fields = func_get_args();
        }

        $this->fields[] = $fields;

        return $this;
    }

    /**
     * Get data as array.
     *
     * @return array
     */
    public function get()
    {
        $ids = $this->pluckIdsFromCollections();

        if (!$ids) {
            return [];
        }

        return $this->getByIds($ids);
    }

    /**
     * Fill fields in each collection.
     *
     * @return void
     */
    public function fill()
    {
        $this->data = $this->get();

        $this->fillCollectionsWithData();
    }

    /**
     * Pluck all ids we need.
     *
     * @return array
     */
    protected function pluckIdsFromCollections()
    {
        $ids = [];
        foreach ($this->collections as $ci => $collection) {
            foreach ($collection as $item) {
                foreach ((array) $this->fields[$ci] as $field) {
                    foreach ((array) $item[$field] as $id) {
                        if ((int) $id) {
                            $ids[] = (int) $id;
                        }
                    }
                }
            }
        }

        return array_unique($ids);
    }

    /**
     * Fill collections with data.
     *
     * @param void
     */
    protected function fillCollectionsWithData()
    {
        foreach ($this->collections as $ci => &$collection) {
            foreach ($collection as $ii => &$item) {
                foreach ((array) $this->fields[$ci] as $field) {
                    $dataFieldName = $field.$this->suffix;

                    if (is_array($item[$field])) {
                        if (empty($item[$field])) {
                            $item[$dataFieldName] = [];
                        } else {
                            foreach ($item[$field] as $id) {
                                $id = (int) $id;
                                if ($id) {
                                    $item[$dataFieldName][$id] = $this->findInLocalDataById($id);
                                }
                            }
                        }
                    } else {
                        $item[$dataFieldName] = $this->findInLocalDataById($item[$field]);
                    }
                }
            }
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    protected function findInLocalDataById($id)
    {
        return $id && isset($this->data[$id]) ? $this->data[$id] : [];
    }
}
