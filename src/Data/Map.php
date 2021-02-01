<?php


namespace App\Data;

class Map
{
    const MAP_SQUARE_START_POS = 'x';
    const MAP_SQUARE_OBSTACLE  = '#';
    const MAP_SQUARE_DROID_PATH = '*';
    const MAP_SQUARE_EMPTY = ' ';

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

    public function getRows() : int
    {
        return count($this->mapData);
    }

    public function getSquare(int $row, int $col) : ?string
    {
        if($row >= $this->getRows() || $col > 8)
        {
            return null;
        }

        return $this->mapData[$row][$col];
    }
}