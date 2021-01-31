<?php


namespace App\Api;


use App\Api\Data\ApiResponse;
use App\Api\Exception\ApiClientException;
use App\Data\Coordinates;
use App\Data\Exception\UnexpectedMapDataException;
use App\Data\Map;
use App\Data\Path;
use App\Map\ReaderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient implements ApiClientInterface
{

    private string $endpoint;
    private ClientInterface $httpClient;
    private ReaderInterface $mapReader;

    private static array $statusCodeToDroidStateMap = [
        417 => ApiResponse::DROID_STATE_CRASHED,
        410 => ApiResponse::DROID_STATE_GONE,
        200 => ApiResponse::DROID_STATE_GOAL_REACHED
    ];

    /**
     * ApiClient constructor.
     * @param string $endpoint
     * @param ClientInterface $httpClient
     * @param ReaderInterface $mapReader
     */
    public function __construct(string $endpoint, ClientInterface $httpClient, ReaderInterface $mapReader)
    {
        $this->endpoint = $endpoint;
        $this->httpClient = $httpClient;
        $this->mapReader = $mapReader;
    }

    /**
     * @param Path $path
     * @param string $senderName
     * @return ApiResponse
     * @throws ApiClientException
     * @throws GuzzleException
     * @throws UnexpectedMapDataException
     */
    public function sendDroid(Path $path, string $senderName): ApiResponse
    {
        $response = $this->httpClient->request('GET', $this->endpoint, [
            'query' => [
                'name' => $senderName,
                'path' => $path->asString()
            ]
        ]);

        if(!isset(self::$statusCodeToDroidStateMap[$response->getStatusCode()]))
        {
            throw new ApiClientException('Unexpected status code: ' . $response->getStatusCode());
        }

        $droidState = self::$statusCodeToDroidStateMap[$response->getStatusCode()];
        $responseData = json_decode($response->getBody(), true);

        if(!isset($responseData['message']))
        {
            throw new ApiClientException('No message available in response data');
        }

        $message = $responseData['message'];

        if(!isset($responseData['map']))
        {
            throw new ApiClientException('No map available in response data');
        }

        $crashPosition = null;

        if($droidState === ApiResponse::DROID_STATE_CRASHED &&
            preg_match('/Crashed at position ([0-9]+),([0-9]+)/', $message, $matches) === 1)
        {
            $crashPosition = Coordinates::create((int) $matches[1], (int) $matches[2]);
        }

        return new ApiResponse(
            $message,
            $this->mapReader->readMapString($responseData['map']),
            $droidState,
            $crashPosition
        );
    }
}