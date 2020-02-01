<?php

namespace Typesense;

class Document
{
    protected $config;
    protected $collection_name;
    protected $document_id;
    protected $api_call;

    public function __construct($config, $collection_name, $document_id)
    {
        $this->config = $config;
        $this->collection_name = $collection_name;
        $this->document_id = $document_id;
        $this->api_call = new ApiCall($config);
    }

    protected function getEndpointPath()
    {
        return sprintf("%s/%s/%s/%s", Collections::RESOURCE_PATH, $this->collection_name,
                                      Documents::RESOURCE_PATH, $this->document_id);
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