<?php

namespace App\Services\MYOB;

use GuzzleHttp\Client;

class Service
{
    protected $client;
    protected $baseUri = 'https://accountrightapi.myob.cloud';
    protected $apiKey;
    protected $apiSecret;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        $this->apiKey = env('MYOB_API_KEY');
        $this->apiSecret = env('MYOB_API_SECRET');
    }

    /**
     * Get the MYOB service.
     *
     * @return string
     */
    public function get($endpoint)
    {
        $response = $this->client->request('GET', $endpoint, [
            'headers' => $this->getHeaders(),
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Post to the MYOB service.
     *
     * @return string
     */
    public function post($endpoint, $data)
    {
        $response = $this->client->request('POST', $endpoint, [
            'headers' => $this->getHeaders(),
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Get headers for the request.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret),
            'Content-Type' => 'application/json',
        ];
    }
}
