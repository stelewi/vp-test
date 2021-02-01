<?php


namespace App\PathFinder;


use App\Api\ApiClientInterface;
use App\Api\Data\ApiResponse;
use App\Data\Path;
use App\PathFinder\Exception\PathFinderException;

class PathFinder
{
    const MAX_DROID_SACRIFICES = 1000;

    private ApiClientInterface $apiClient;

    private PathSuggesterInterface $pathSuggester;

    private string $droidSenderName;

    /**
     * PathFinder constructor.
     * @param ApiClientInterface $apiClient
     * @param PathSuggesterInterface $pathSuggester
     * @param string $droidSenderName
     */
    public function __construct(ApiClientInterface $apiClient, PathSuggesterInterface $pathSuggester, string $droidSenderName)
    {
        $this->apiClient = $apiClient;
        $this->pathSuggester = $pathSuggester;
        $this->droidSenderName = $droidSenderName;
    }


    public function findPath(): Path
    {
        $droidFollowPath = $this->pathSuggester->initialSuggestion();
        $droidNumber = 0;

        while($droidNumber++ < self::MAX_DROID_SACRIFICES)
        {
            $droidResponse = $this->apiClient->sendDroid($droidFollowPath, $this->droidSenderName);

            switch ($droidResponse->getDroidState())
            {
                case ApiResponse::DROID_STATE_GOAL_REACHED:
                    // now generate the optimal path from the full map
                    return $this->pathSuggester->informedSuggestion($droidResponse->getMap());

                case ApiResponse::DROID_STATE_GONE:
                case ApiResponse::DROID_STATE_CRASHED:
                    // drone either vanished or crashed; use our map to generate a potentially better path
                    $droidFollowPath = $this->pathSuggester->informedSuggestion($droidResponse->getMap());
            }
        }
    }
}