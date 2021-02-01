<?php


namespace App\Map;


use App\Map\Exception\UnexpectedMapDataException;
use App\Data\Map;

class MapReader implements MapReaderInterface
{
    /**
     * @param string $mapString
     * @return Map
     * @throws UnexpectedMapDataException
     */
    public function readMapString(string $mapString): Map
    {
        $rows = preg_split("/\\r\\n|\\r|\\n/", $mapString);

        /**
         * @var array<int,array<int,string>>
         */
        $mapData = [];

        foreach ($rows as $rowIdx => $row)
        {
            $squares = str_split($row);

            if(count($squares) !== 9)
            {
                throw new UnexpectedMapDataException("Expected 9 squares in a row but got " .
                    count($squares) . ".\nMap data: $mapString"
                );
            }

            foreach ($squares as $colIdx => $square)
            {
                if(!in_array($square, [Map::MAP_SQUARE_DROID_PATH, Map::MAP_SQUARE_OBSTACLE, Map::MAP_SQUARE_START_POS, Map::MAP_SQUARE_EMPTY]))
                {
                    throw new UnexpectedMapDataException("Unexpected character in map data: [$square] " .
                        ".\nMap data: $mapString"
                    );
                }

                $mapData[$rowIdx][$colIdx] = $square;
            }
        }

        return Map::fromMapData($mapData);
    }

}