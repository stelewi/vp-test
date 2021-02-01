<?php


namespace App\Data;


class Coordinates
{
    private int $x;
    private int $y;

    /**
     * Coordinates constructor.
     * @param int $x
     * @param int $y
     */
    private function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    public function move(Move $move) : self
    {
        $offset = $move->getOffset();

        return self::create(
            $this->x + $offset->getX(),
            $this->y + $offset->getY()
        );
    }


    public static function create(int $x, int $y): self
    {
        return new self($x, $y);
    }
}