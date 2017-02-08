<?php

namespace Arrilot\Tanker;

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
     * Data keyed by id.
     *
     * @var array
     */
    protected $data = [];
    
    /**
     * Fetch data for given ids.
     *
     * @param array $ids
     * @return array
     */
    abstract protected function fetch(array $ids);
    
    /**
     * Add collection
     *
     * @param $collection
     * @return $this
     */
    public function collection(&$collection)
    {
        $this->collections[] = &$collection;

        return $this;
    }
    
    /**
     * Add fields.
     *
     * @param $fields
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
     * Fill fields in each collection.
     *
     * @return void
     */
    public function fill()
    {
        $ids = $this->pluckIdsFromCollections();

        $this->data = $this->fetch($ids);

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
                        if ((int)$id) {
                            $ids[] = (int)$id;
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
                    if (is_array($item[$field])) {
                        foreach ($item[$field] as $id) {
                            $item[$field . "_data"][] = $this->findDataById($id);
                        }
                    } else {
                        $item[$field . "_data"] = $this->findDataById($item[$field]);
                    }
                }
            }
        }
    }
    
    /**
     * @param $id
     * @return array
     */
    protected function findDataById($id)
    {
        return $id && isset($this->data[$id]) ? $this->data[$id] : [];
    }
}