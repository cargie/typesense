<?php

namespace Typesense;

class Node
{
    public $host, $port, $protocol, $api_key;

    public function __construct($host, $port, $protocol, $api_key)
    {
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->api_key = $api_key;
    }

    public function getUrl()
    {
        return sprintf("%s://%s:%s", $this->protocol, $this->host, $this->port);
    }

    public static function validateNodeFields($node)
    {
        $expected_fields = ['host', 'port', 'protocol', 'api_key'];

        return count(array_diff($expected_fields, $node)) === 0;
    }
}