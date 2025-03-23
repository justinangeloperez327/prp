<?php

namespace App\Services\MYOB;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class Client
{
    protected $client;

    protected $apiKey;

    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = 'https://accountrightapi.myob.cloud';
        $this->apiKey = config('services.myob.api_key');

        $this->client = new GuzzleClient([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a POST request to the MYOB API.
     *
     * @throws Exception
     */
    public function post(string $endpoint, array $data): array
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new Exception('MYOB API POST request failed: '.$e->getMessage());
        }
    }

    /**
     * Send a GET request to the MYOB API.
     *
     * @throws Exception
     */
    public function get(string $endpoint): array
    {
        try {
            $response = $this->client->get($endpoint);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new Exception('MYOB API GET request failed: '.$e->getMessage());
        }
    }
}
