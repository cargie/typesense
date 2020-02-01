<?php

namespace Typesense;

use ArrayAccess;
use Typesense\Http\Client;

class Collections implements ArrayAccess
{
    const RESOURCE_PATH = '/collections';

    protected $collections = [];

    protected $api_call;

    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->api_call = new ApiCall($this->configuration);
    }

    public function create($schema){
        return $this->api_call->post(self::RESOURCE_PATH, $schema);
    }

    public function retrieve(){
        return $this->api_call->get(sprintf('%s', self::RESOURCE_PATH));
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->collections[] = $value;
        } else {
            $this->collections[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->collections[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->collections[$offset]);
    }

    public function offsetGet($offset) {
        if(!$this->offsetExists($offset)) {
            $this->collections[$offset] = new Collection($this->configuration, $offset);
        }
        return $this->collections[$offset];
    }
}