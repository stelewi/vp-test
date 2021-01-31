<?php


namespace App\Data;


use App\Data\Exception\UnexpectedMapDataException;

class Map
{
    const MAP_SQUARE_START_POS = 'x';
    const MAP_SQUARE_OBSTACLE  = '#';
    const MAP_SQUARE_DROID_PATH = '*';


    /**
     * @var array<int,array<int,string>>
     */
    private array $mapData;

    /**
     * Map constructor.
     * @param array $mapData
     */
    private function __construct(array $mapData)
    {
        $this->mapData = $mapData;
    }

    public static function fromMapData(array $mapData)
    {
        return new self($mapData);
    }
}