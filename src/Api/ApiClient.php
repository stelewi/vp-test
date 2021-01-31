<?php


namespace App\Api;


use GuzzleHttp\ClientInterface;

class ApiClient
{

    private string $endpoint;
    private string $nameKey;
    private ClientInterface $httpClient;

    /**
     * Client constructor.
     * @param string $endpoint
     * @param string $nameKey
     * @param ClientInterface $httpClient
     */
    public function __construct(string $endpoint, string $nameKey, ClientInterface $httpClient)
    {
        $this->endpoint = $endpoint;
        $this->nameKey = $nameKey;
        $this->httpClient = $httpClient;
    }


}