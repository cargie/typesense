<?php

namespace Typesense;

class Collection
{
    protected $api_call;
    protected $config;
    protected $name;
    public $documents = [];

    public function __construct($config, $name)
    {
        $this->config = $config;
        $this->name = $name;
        $this->api_call = new ApiCall($config);
        $this->documents = new Documents($config, $name);
    }

    protected function getEndpointPath()
    {
        return sprintf("%s/%s", Collections::RESOURCE_PATH, $this->name);
    }

    public function retrieve()
    {
        return $this->api_call->get($this->getEndpointPath());
    }

    public function delete()
    {
        return $this->api_call->delete($this->getEndpointPath());
    }
}