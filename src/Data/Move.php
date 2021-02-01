<?php


namespace App\Data;


class Move
{
    const FORWARD = 'f';
    const LEFT    = 'l';
    const RIGHT   = 'r';

    private string $direction;

    /**
     * Move constructor.
     * @param string $direction
     */
    private function __construct(string $direction)
    {
        $this->direction = $direction;
    }

    public static function forward() : self
    {
        return new self(self::FORWARD);
    }

    public static function left() : self
    {
        return new self(self::LEFT);
    }

    public static function right() : self
    {
        return new self(self::RIGHT);
    }

    public function getOffset(): Coordinates
    {
        switch ($this->direction)
        {
            case self::FORWARD:
                return Coordinates::create(1,0);

            case self::LEFT:
                return Coordinates::create(0,-1);

            case self::RIGHT:
                return Coordinates::create(0,1);
        }
    }

    public function __toString() : string
    {
        return $this->direction;
    }
}