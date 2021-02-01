<?php


namespace App\Api;


use App\Api\Data\ApiResponse;
use App\Api\Exception\ApiClientException;
use App\Data\Coordinates;
use App\Data\Path;
use App\Map\MapReaderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class ApiClient implements ApiClientInterface
{
    use LoggerAwareTrait;

    private string $endpoint;
    private ClientInterface $httpClient;
    private MapReaderInterface $mapReader;

    private static array $statusCodeToDroidStateMap = [
        417 => ApiResponse::DROID_STATE_CRASHED,
        410 => ApiResponse::DROID_STATE_GONE,
        200 => ApiResponse::DROID_STATE_GOAL_REACHED
    ];

    /**
     * ApiClient constructor.
     * @param string $endpoint
     * @param ClientInterface $httpClient
     * @param MapReaderInterface $mapReader
     */
    public function __construct(string $endpoint, ClientInterface $httpClient, MapReaderInterface $mapReader)
    {
        $this->endpoint = $endpoint;
        $this->httpClient = $httpClient;
        $this->mapReader = $mapReader;
        $this->setLogger(new NullLogger());
    }

    /**
     * @param Path $path
     * @param string $senderName
     * @return ApiResponse
     * @throws ApiClientException
     */
    public function sendDroid(Path $path, string $senderName): ApiResponse
    {
        $this->logger->info("Sending droid on path: ". $path->asString());

        $response = $this->httpClient->request('GET', $this->endpoint, [
            'query' => [
                'name' => $senderName,
                'path' => $path->asString()
            ],
            'http_errors' => false
        ]);

        if(!isset(self::$statusCodeToDroidStateMap[$response->getStatusCode()]))
        {
            throw new ApiClientException('Unexpected status code: ' . $response->getStatusCode());
        }

        $droidState = self::$statusCodeToDroidStateMap[$response->getStatusCode()];
        $responseData = json_decode($response->getBody(), true);

        if($responseData === null)
        {
            throw new ApiClientException('Could not decode response: ' . $responseData);
        }

        if(!isset($responseData['message']))
        {
            throw new ApiClientException('No message available in response data');
        }

        $message = $responseData['message'];

        if(!isset($responseData['map']))
        {
            throw new ApiClientException('No map available in response data');
        }

        return new ApiResponse(
            $message,
            $this->mapReader->readMapString($responseData['map']),
            $droidState,
            $this->getDroidCrashPosition($droidState, $message)
        );
    }

    /**
     * @param int $droidState
     * @param string $message
     * @return Coordinates|null
     */
    private function getDroidCrashPosition(int $droidState, string $message) : ?Coordinates
    {
        if($droidState === ApiResponse::DROID_STATE_CRASHED &&
            preg_match('/Crashed at position ([0-9]+),([0-9]+)/', $message, $matches) === 1)
        {
            return Coordinates::create((int) $matches[1], (int) $matches[2]);
        }

        return null;
    }
}