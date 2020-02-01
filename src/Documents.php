<?php

namespace Typesense;

use ArrayAccess;

class Documents implements ArrayAccess
{
    const RESOURCE_PATH = 'documents';

    protected $configuration;
    protected $api_call;
    protected $collection_name;
    protected $documents = [];


    public function __construct($configuration, $collection_name)
    {
        $this->configuration = $configuration;
        $this->collection_name = $collection_name;
        $this->api_call = new ApiCall($configuration);
    }

    protected function getEndpointPath($action = '')
    {
        return sprintf("%s/%s/%s/%s", Collections::RESOURCE_PATH, $this->collection_name, Documents::RESOURCE_PATH, $action);
    }

    public function create($document)
    {
        return $this->api_call->post($this->getEndpointPath(), $document);
    }

    public function export()
    {
        $response = $this->api_call->get($this->getEndpointPath('export'), [], false);

        return explode("\n", $response);
    }

    public function search($search_parameters)
    {
        return $this->api_call->get($this->getEndpointPath('search'), $search_parameters);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->documents[] = $value;
        } else {
            $this->documents[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->documents[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->documents[$offset]);
    }

    public function offsetGet($offset) {
        if(!$this->offsetExists($offset)) {
            $this->documents[$offset] = new Document($this->configuration, $this->collection_name, $offset);
        }
        return $this->documents[$offset];
    }
}