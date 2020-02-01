<?php

namespace Typesense;

class Client
{
    public $collections;
    protected $api_call;

    public function __construct(
        $options
    ) {
        $configuration = new Configuration($options);
        $this->collections = new Collections($configuration);
        $this->api_call = new ApiCall($configuration);
    }

    public function health()
    {
        return $this->api_call->get('/health');
    }
}