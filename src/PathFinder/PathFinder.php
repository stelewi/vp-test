<?php


namespace App\PathFinder;


use App\Api\ApiClientInterface;
use App\Api\Data\ApiResponse;
use App\Data\Move;
use App\Data\Path;
use App\PathFinder\Exception\PathFinderException;

class PathFinder
{
    const MAX_DROID_SACRIFICES = 5000;

    private ApiClientInterface $apiClient;

    private PathSuggesterInterface $pathSuggester;

    /**
     * PathFinder constructor.
     * @param ApiClientInterface $apiClient
     * @param PathSuggesterInterface $pathSuggester
     */
    public function __construct(ApiClientInterface $apiClient, PathSuggesterInterface $pathSuggester)
    {
        $this->apiClient = $apiClient;
        $this->pathSuggester = $pathSuggester;
    }

    /**
     * @param string $droidSenderName
     * @param ProgressTracker|null $tracker
     * @return Path
     */
    public function findPath(string $droidSenderName, ProgressTracker $tracker = null): Path
    {
        $droidFollowPath = $this->pathSuggester->initialSuggestion();
        $droidNumber = 0;

        while($droidNumber++ < self::MAX_DROID_SACRIFICES)
        {
            $droidResponse = $this->apiClient->sendDroid($droidFollowPath, $droidSenderName);

            if($tracker !== null)
            {
                $tracker->updateProgress($droidResponse);
            }

            switch ($droidResponse->getDroidState())
            {
                case ApiResponse::DROID_STATE_GOAL_REACHED:
                    // now generate the optimal path from the full map
                    return $this->pathSuggester->informedSuggestion($droidResponse->getMap());

                case ApiResponse::DROID_STATE_GONE:
                    // make an informed choice of path based on map...
                    $droidFollowPath = $this->pathSuggester->informedSuggestion($droidResponse->getMap());
                    // and forge further ahead if possible...
                    $droidFollowPath = $droidFollowPath->addPath(Path::create(array_fill(0, 20, Move::forward())));
                    break;

                case ApiResponse::DROID_STATE_CRASHED:
                    $droidFollowPath = $this->pathSuggester->informedSuggestion($droidResponse->getMap());
                    break;
            }
        }
    }
}