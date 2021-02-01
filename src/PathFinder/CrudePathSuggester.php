<?php


namespace App\PathFinder;


use App\Data\Coordinates;
use App\Data\Map;
use App\Data\Move;
use App\Data\Path;
use App\Data\PointOnMap;

class CrudePathSuggester implements PathSuggesterInterface
{
    /**
     * make a path suggestion without a map
     * @return Path
     */
    public function initialSuggestion(): Path
    {
        // we have no idea so just send a drone forward until it crashes or stops
        return Path::create(array_fill(0, 20, Move::forward()));
    }

    /**
     * make a path suggestion with a partial map
     * @param Map $map
     * @return Path
     */
    public function informedSuggestion(Map $map): Path
    {
        $path = Path::empty();
        $startPosition = Coordinates::create(0,4);
        $pointOnMap = PointOnMap::create($map, $startPosition);

        while($pointOnMap->getPosition()->getX() < $map->getRows())
        {
            $currentPosition = $pointOnMap->getPosition();
            $leastMovesToNextPosition = null;
            $leastMovesPath = null;

            for($y = 0; $y < 9; $y++)
            {
                $yOffset = $y - $currentPosition->getY();
                $testMoves = array_fill(0, abs($yOffset), $yOffset < 0 ? Move::left() : Move::right());
                $testPath = Path::create(array_merge($testMoves, [Move::forward()]));
                $movedToPointOnMap = $pointOnMap->move($testPath);

                // invalid move?
                if($movedToPointOnMap === null)
                {
                    continue;
                }

                if($leastMovesToNextPosition === null || $leastMovesToNextPosition > count($testPath->getMoves()))
                {
                    $leastMovesToNextPosition = count($testPath->getMoves());
                    $leastMovesPath = $testPath;
                }
            }

            $pointOnMap = $pointOnMap->move($leastMovesPath);
            $path = $path->addPath($leastMovesPath);
        }

        return $path;
    }
}