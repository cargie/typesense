<?php

namespace Typesense;

use GuzzleHttp\Client;
use Exception;
use Typesense\Exceptions\ {
    ObjectAlreadyExists,
    ObjectNotFound,
    ObjectUnprocessable,
    RequestMalformed,
    RequestUnauthorized,
    ServerError,
    TypesenseClientError
};
use GuzzleHttp\Exception\ClientException;

class ApiCall
{
    const API_KEY_HEADER_NAME = 'X-TYPESENSE-API-KEY';

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getNodes()
    {
        return (object) array_merge([$this->config->master_node], $this->config->read_replica_nodes);
    }

    public static function getExeptions($status_code, $message)
    {
        switch ($status_code) {
            case 400:
                return new RequestMalformed($message, $status_code);
            case 401:
                return new RequestUnauthorized($message, $status_code);
            case 404:
                return new ObjectNotFound($message, $status_code);
            case 409:
                return new ObjectAlreadyExists($message, $status_code);
            case 422:
                return new ObjectUnprocessable($message, $status_code);
            case 500:
                return new ServerError($message, $status_code);
            default:
                return new TypesenseClientError($message, $status_code);
        }
    }


    public function get($endpoint, $params = [], $as_json = true)
    {
        $params = $params;

        foreach ($this->getNodes() as $node) {

            $client = new Client([
                'base_uri' => $node->getUrl(),
            ]);
            $response = null;
            try {
                $response = $client->request('GET', $endpoint, [
                    'headers' => [
                        self::API_KEY_HEADER_NAME => $node->api_key
                    ],
                    'query' => $params,
                    'timeout' => $this->config->timeout_seconds
                ]);


                if ($response->getStatusCode() === 200) {

                    if ($as_json) {
                        return json_decode($response->getBody(), true);
                    }
                    return $response->getBody();
                } else {
                    $error_message = json_decode($response->getBody())['message'] ?? 'API Error';

                    throw self::getExeptions($response->getStatusCode(), $error_message);
                }

            } catch (ClientException $e) {

                $error_message = json_decode($e->getResponse()->getBody(), true)['message'] ?? 'API Error';

                throw self::getExeptions($e->getCode(), $error_message);
            }
            
        }
    }

    public function post($endpoint, $body)
    {
        $url = $this->config->master_node->getUrl();
        $api_key = $this->config->master_node->api_key;

        $client = new Client([
            'base_uri' => $url
        ]);

        try {
            $response = $client->request('POST', $endpoint, [
                'headers' => [
                    ApiCall::API_KEY_HEADER_NAME => $api_key
                ],
                'json' => $body,
                'timeout' => $this->config->timeout_seconds,
            ]);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
                $error_message = json_decode($response->getBody(), true)['message'] ?? 'API Error';

                throw self::getExeptions($response->getStatusCode(), $error_message);
            }

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $error_message = json_decode($e->getResponse()->getBody(), true)['message'] ?? 'API Error';

            throw self::getExeptions($e->getCode(), $error_message);
        }
    }

    public function put($endpoint, $body)
    {
        $url = $this->config->master_node->getUrl();
        $api_key = $this->config->master_node->api_key;

        $client = new Client([
            'base_uri' => $url
        ]);

        try {
            $response = $client->request('PUT', $endpoint, [
                'headers' => [
                    ApiCall::API_KEY_HEADER_NAME => $api_key
                ],
                'json' => $body,
                'timeout' => $this->config->timeout_seconds,
            ]);

            if ($response->getStatusCode() !== 200) {

                $error_message = json_decode($response->getBody(), true)['message'] ?? 'API Error';

                throw self::getExeptions($response->getStatusCode(), $error_message);
            }

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            
            $error_message = json_decode($e->getResponse()->getBody(), true)['message'] ?? 'API Error';

            throw self::getExeptions($e->getCode(), $error_message);
        }
    }

    public function delete($endpoint)
    {
        $url = $this->config->master_node->getUrl();
        $api_key = $this->config->master_node->api_key;

        $client = new Client([
            'base_uri' => $url
        ]);

        try {
            $response = $client->request('DELETE', $endpoint, [
                'headers' => [
                    ApiCall::API_KEY_HEADER_NAME => $api_key
                ],
                'timeout' => $this->config->timeout_seconds,
            ]);

            if ($response->getStatusCode() !== 200) {

                $error_message = json_decode($response->getBody(), true)['message'] ?? 'API Error';

                throw self::getExeptions($response->getStatusCode(), $error_message);
            }

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            
            $error_message = json_decode($e->getResponse()->getBody(), true)['message'] ?? 'API Error';

            throw self::getExeptions($e->getCode(), $error_message);   
        }
    }

}