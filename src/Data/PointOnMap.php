<?php


namespace App\Data;


class PointOnMap
{
    private Map $map;
    private Coordinates $position;

    /**
     * PointOnMap constructor.
     * @param Map $map
     * @param Coordinates $position
     */
    private function __construct(Map $map, Coordinates $position)
    {
        $this->map = $map;
        $this->position = $position;
    }

    /**
     * @return Map
     */
    public function getMap(): Map
    {
        return $this->map;
    }

    /**
     * @return Coordinates
     */
    public function getPosition(): Coordinates
    {
        return $this->position;
    }

    /**
     * return an updated
     * @param Path $path
     * @return PointOnMap|null
     */
    public function move(Path $path) : ?PointOnMap
    {
        $currentPosition = $this->position;

        foreach ($path->getMoves() as $move)
        {
            $currentPosition = $currentPosition->move($move);

            // we hit an obstacle
            if($this->map->getSquare($currentPosition) === Map::MAP_SQUARE_OBSTACLE)
            {
                return null;
            }
        }

        return self::create($this->map, $currentPosition);
    }

    public static function create(Map $map, Coordinates $position) : self
    {
        return new self($map, $position);
    }
}