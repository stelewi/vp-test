<?php


namespace App\Data;


class Path
{
    /**
     * @var Move[]
     */
    private array $moves;

    /**
     * Path constructor.
     * @param Move[] $moves
     */
    private function __construct(array $moves)
    {
        $this->moves = $moves;
    }

    /**
     * @return Move[]
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    public function addPath(Path $path)
    {
        // array merge isn't the fastest so could optimize this
        return self::create(array_merge($this->moves, $path->getMoves()));
    }

    public function asString() : string
    {
        return implode('', $this->moves);
    }

    public static function create(array $moves) : self
    {
        return new self($moves);
    }

    public static function empty() : self
    {
        return new self([]);
    }

}