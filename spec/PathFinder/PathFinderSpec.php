<?php

namespace spec\App\PathFinder;

use App\Api\ApiClientInterface;
use App\Api\Data\ApiResponse;
use App\Data\Map;
use App\Data\Path;
use App\PathFinder\PathFinder;
use App\PathFinder\PathSuggesterInterface;
use PhpSpec\ObjectBehavior;

class PathFinderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PathFinder::class);
    }

    function let(ApiClientInterface $apiClient, PathSuggesterInterface $pathSuggester)
    {
        $this->beConstructedWith($apiClient, $pathSuggester, 'Chewbacca');
    }

    function it_sends_droids_until_a_path_is_found(
        ApiClientInterface $apiClient,
        PathSuggesterInterface $pathSuggester,
        Path $pathA,
        ApiResponse $apiResponseA,
        Map $mapResponseA,
        Path $pathB,
        ApiResponse $apiResponseB,
        Map $mapResponseB,
        Path $pathC,
        ApiResponse $apiResponseC,
        Map $mapResponseC,
        Path $optimalPath
    )
    {
        // initial call to the path suggested
        $pathSuggester->initialSuggestion()->willReturn($pathA);
        $apiClient->sendDroid($pathA, 'Chewbacca')->willReturn($apiResponseA);
        $apiResponseA->getMap()->willReturn($mapResponseA);
        $apiResponseA->getDroidState()->willReturn(ApiResponse::DROID_STATE_GONE);

        // use map acquired from original droid to send the next droid
        $pathSuggester->informedSuggestion($mapResponseA)->willReturn($pathB);
        $apiClient->sendDroid($pathB, 'Chewbacca')->willReturn($apiResponseB);
        $apiResponseB->getMap()->willReturn($mapResponseB);
        $apiResponseB->getDroidState()->willReturn(ApiResponse::DROID_STATE_CRASHED);

        // use map acquired to send the next droid
        $pathSuggester->informedSuggestion($mapResponseB)->willReturn($pathC);
        $apiClient->sendDroid($pathC, 'Chewbacca')->willReturn($apiResponseC);
        $apiResponseC->getMap()->willReturn($mapResponseC);
        $apiResponseC->getDroidState()->willReturn(ApiResponse::DROID_STATE_GOAL_REACHED);

        $pathSuggester->informedSuggestion($mapResponseC)->willReturn($optimalPath);

        $this->findPath()->shouldBe($optimalPath);
    }
}
