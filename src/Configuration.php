<?php

namespace Typesense;

use Typesense\Exceptions\ConfigError;

class Configuration
{
    public $master_node;
    public $read_replica_nodes = [];
    public $timeout_seconds = 1.0;

    public function __construct($config)
    {
        Configuration::validateConfig($config);

        $master_node = $config['master_node'] ?? null;

        $read_replica_nodes = $config['read_replica_nodes'] ?? [];

        $this->master_node = new Node($master_node['host'], $master_node['port'], $master_node['protocol'], $master_node['api_key']);

        foreach ($read_replica_nodes as $replica_node) {
            $this->read_replica_nodes[] = new Node($replica_node['host'],$replica_node['port'],
                                             $replica_node['protocol'], $replica_node['api_key']);
        }

        $this->timeout_seconds = $config['timeout_seconds'] ?? 1.0;
    }

    public static function validateConfig($config)
    {
        if (!isset($config['master_node'])) {
            throw new ConfigError('`master_node` is not defined.');
        }

        if(!Node::validateNodeFields(array_keys($config['master_node']))) {
            throw new ConfigError('`master_node` entry be an array with the following required keys: host, port, protocol, api_key');
        }

        foreach(@$config['read_replica_nodes'] ?? [] as $replica_node) {
            if (!Node::validateNodeFields(array_keys($replica_node))) {
                throw new ConfigError('`read_replica_nodes` entry be an array with the following required keys: host, port, protocol, api_key');
            }
        }
    }
}